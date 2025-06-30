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

## Usage Example

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

### 2. Callback Handler

```php
$gateway = Omnipay::create('AzeriCard');
$gateway->setPublicKeyPath(storage_path('keys/azericard-public.pem'));

$response = $gateway->completePurchase()->send();

if ($response->isSuccessful()) {
    $reference = $response->getTransactionReference(); // INT_REF
    // mark order as paid
} else {
    // handle failed or invalid response
}
```

---

### 3. Refund

```php
$response = $gateway->refund([
    'amount'        => '10.00',
    'currency'      => 'AZN',
    'transactionId' => 'ORDER12345',
    'rrn'           => '317276406077',
    'intRef'        => 'ABC123XYZ987',
])->send();

if ($response->isSuccessful()) {
    echo "Refund successful!";
} else {
    echo "Refund failed: " . $response->getMessage();
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
- [x] Refund (TRTYPE 22)
- [x] Verify P_SIGN signatures
- [x] Sales completion (TRTYPE 21)
- [x] Status check (TRTYPE 90)
- [x] Tokenization support

---

## License

MIT © [Elnur Akhudov](mailto:elnurxf@gmail.com)
