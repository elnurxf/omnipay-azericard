# Omnipay: AzeriCard

**AzeriCard gateway for [Omnipay](https://github.com/thephpleague/omnipay)**

This package provides AzeriCard e-commerce gateway integration with support for 3D Secure, refunds, and signature verification.

> Built and maintained by [Elnur Akhudov](mailto:elnurxf@gmail.com)

---

## Installation

Install via Composer:

```bash
composer require elnurxf/omnipay-azericard
```

---

## Usage Examples

### 1. Purchase Request (3D Redirect Flow)

```php
use Omnipay\Omnipay;

$gateway = Omnipay::create('AzeriCard');
$gateway->setTerminalId('YOUR_TERMINAL_ID');
$gateway->setPrivateKeyPath(storage_path('keys/private.pem')); // PEM file
$gateway->setTestMode(true); // or false for production

$response = $gateway->purchase([
    'amount'         => '20.00',
    'currency'       => 'AZN',
    'transactionId'  => 'ORDER12345',
    'returnUrl'      => route('payment.callback'),
    'description'    => 'Order #12345',
    'email'          => 'customer@example.com',
    'name'           => 'John Doe',
])->send();

if ($response->isRedirect()) {
    $response->redirect(); // POST to AzeriCard
} else {
    echo "Error: " . $response->getMessage();
}
```

---

### 2. Authorization (Pre-Auth) Request

```php
// Step 1: Create authorization request
$response = $gateway->authorize([
    'amount'         => '50.00',
    'currency'       => 'AZN',
    'transactionId'  => 'AUTH12345',
    'returnUrl'      => route('payment.auth.callback'),
    'description'    => 'Authorization for Order #12345',
    'email'          => 'customer@example.com',
    'name'           => 'John Doe',
])->send();

if ($response->isRedirect()) {
    $response->redirect(); // Redirect to AzeriCard for 3D Secure
} else {
    echo "Authorization Error: " . $response->getMessage();
}
```

---

### 3. Callback Handler (Complete Purchase/Authorization)

```php
$gateway = Omnipay::create('AzeriCard');
$gateway->setPublicKeyPath(storage_path('keys/azericard-public.pem'));

$response = $gateway->completePurchase()->send();

if ($response->isSuccessful()) {
    $reference = $response->getTransactionReference(); // INT_REF
    $rrn = $response->getRRN(); // Retrieval Reference Number
    $amount = $response->getAmount();
    $action = $response->getAction(); // '0' for success
    
    // For purchase transactions
    if ($response->getTransactionType() === '1') {
        // Mark order as paid
        echo "Payment successful! Reference: {$reference}, RRN: {$rrn}";
    }
    
    // For authorization transactions  
    if ($response->getTransactionType() === '0') {
        // Authorization successful - funds are reserved
        echo "Authorization successful! Reference: {$reference}, RRN: {$rrn}";
        // Store these values for later capture
    }
} else {
    echo "Transaction failed: " . $response->getMessage();
}
```

---

### 4. Capture (Complete Sale) - Complete Pre-Authorized Transaction

```php
// Step 2: Capture the authorized amount (must be done after successful authorization)
$response = $gateway->completeSale([
    'amount'        => '50.00', // Can be less than or equal to authorized amount
    'currency'      => 'AZN',
    'transactionId' => 'AUTH12345', // Original authorization transaction ID
    'rrn'           => '317276406077', // RRN from authorization callback
    'intRef'        => 'ABC123XYZ987', // INT_REF from authorization callback
])->send();

if ($response->isSuccessful()) {
    echo "Capture successful! Funds have been charged.";
    $captureReference = $response->getTransactionReference();
} else {
    echo "Capture failed: " . $response->getMessage();
}
```

---

### 5. Refund

```php
$response = $gateway->refund([
    'amount'        => '10.00', // Refund amount (can be partial)
    'currency'      => 'AZN',
    'transactionId' => 'ORDER12345', // Original transaction ID
    'rrn'           => '317276406077', // RRN from original transaction
    'intRef'        => 'ABC123XYZ987', // INT_REF from original transaction
])->send();

if ($response->isSuccessful()) {
    echo "Refund successful!";
    $refundReference = $response->getTransactionReference();
} else {
    echo "Refund failed: " . $response->getMessage();
}
```

---

### 6. Void (Cancel Authorization)

```php
// Cancel/void an authorization before it's captured
$response = $gateway->void([
    'transactionId' => 'AUTH12345', // Original authorization transaction ID
    'rrn'           => '317276406077', // RRN from authorization
    'intRef'        => 'ABC123XYZ987', // INT_REF from authorization
])->send();

if ($response->isSuccessful()) {
    echo "Authorization voided successfully!";
} else {
    echo "Void failed: " . $response->getMessage();
}
```

---

### 7. Transaction Status Check

```php
$response = $gateway->status([
    'transactionId' => 'ORDER12345',
    'rrn'           => '317276406077',
    'intRef'        => 'ABC123XYZ987',
])->send();

if ($response->isSuccessful()) {
    $status = $response->getStatus();
    $amount = $response->getAmount();
    echo "Transaction Status: {$status}, Amount: {$amount}";
} else {
    echo "Status check failed: " . $response->getMessage();
}
```

---

### 8. Complete Payment Flow Example

```php
// Step 1: Authorization
$authResponse = $gateway->authorize([
    'amount' => '100.00',
    'transactionId' => 'ORDER12345',
    'returnUrl' => route('auth.callback'),
    // ... other parameters
])->send();

// Step 2: Handle authorization callback
$callbackResponse = $gateway->completePurchase()->send();
if ($callbackResponse->isSuccessful()) {
    $rrn = $callbackResponse->getRRN();
    $intRef = $callbackResponse->getTransactionReference();
    
    // Step 3a: Capture the full amount
    $captureResponse = $gateway->completeSale([
        'amount' => '100.00',
        'transactionId' => 'ORDER12345',
        'rrn' => $rrn,
        'intRef' => $intRef,
    ])->send();
    
    // OR Step 3b: Void the authorization
    $voidResponse = $gateway->void([
        'transactionId' => 'ORDER12345',
        'rrn' => $rrn,
        'intRef' => $intRef,
    ])->send();
}
```

---

## Security

The gateway signs and verifies all transactions using **RSA-SHA256**. Make sure to:

- Keep your **private key** secure and readable only by your application.
- Use the **public key** provided by AzeriCard to verify callbacks.

---

## Roadmap / TODO

- [x] Purchase (TRTYPE 1)
- [x] Authorization/Pre-Auth (TRTYPE 0)
- [x] Sales completion/Capture (TRTYPE 21)
- [x] Refund (TRTYPE 22)
- [x] Status check (TRTYPE 90)
- [x] Void authorization
- [x] Verify P_SIGN signatures
- [ ] Tokenization support
- [ ] Recurring payments
- [ ] Multi-currency support enhancement

---

## License

MIT © [Elnur Akhudov](mailto:elnurxf@gmail.com)
