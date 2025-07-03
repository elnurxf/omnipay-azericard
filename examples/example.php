<?php

require_once 'vendor/autoload.php';

use Omnipay\Omnipay;

// Create the gateway instance directly
$gateway = Omnipay::create('AzeriCard');

// Configure the gateway
$gateway->setTerminalId('YOUR_TERMINAL_ID');
$gateway->setPrivateKeyPath('/path/to/your/private.pem');
$gateway->setPublicKeyPath('/path/to/your/public.pem');
$gateway->setTestMode(true); // or false for production

// Example 1: Purchase Request (Direct Payment)
echo "=== Purchase Example ===\n";
$purchaseResponse = $gateway->purchase([
    'amount'        => '20.00',
    'currency'      => 'AZN',
    'transactionId' => '12345',
    'returnUrl'     => 'https://yoursite.com/callback',
    'description'   => 'Order #12345',
    'email'         => 'customer@example.com',
    'name'          => 'John Doe',
])->send();

if ($purchaseResponse->isRedirect()) {
    echo "Redirecting to AzeriCard for payment...\n";
    echo "Redirect URL: " . $purchaseResponse->getRedirectUrl() . "\n";
    // In real application: $purchaseResponse->redirect();
} else {
    echo "Error: " . $purchaseResponse->getMessage() . "\n";
}

// Example 2: Authorization Request (Pre-Auth)
echo "\n=== Authorization Example ===\n";
$authResponse = $gateway->authorize([
    'amount'        => '50.00',
    'currency'      => 'AZN',
    'transactionId' => 'AUTH12345',
    'returnUrl'     => 'https://yoursite.com/auth-callback',
    'description'   => 'Authorization for Order #12345',
    'email'         => 'customer@example.com',
    'name'          => 'John Doe',
])->send();

if ($authResponse->isRedirect()) {
    echo "Redirecting to AzeriCard for authorization...\n";
    echo "Redirect URL: " . $authResponse->getRedirectUrl() . "\n";
    // In real application: $authResponse->redirect();
} else {
    echo "Authorization Error: " . $authResponse->getMessage() . "\n";
}

// Example 3: Handle Callback (Complete Purchase/Authorization)
echo "\n=== Callback Handler Example ===\n";
$callbackResponse = $gateway->completePurchase()->send();

if ($callbackResponse->isSuccessful()) {
    $reference = $callbackResponse->getTransactionReference();
    $rrn       = $callbackResponse->getRRN();
    $trtype    = $callbackResponse->getTransactionType();

    if ($trtype === '1') {
        echo "Payment successful! Reference: {$reference}, RRN: {$rrn}\n";
    } elseif ($trtype === '0') {
        echo "Authorization successful! Reference: {$reference}, RRN: {$rrn}\n";
        echo "Funds are reserved and can be captured later.\n";
    }
} else {
    echo "Transaction failed: " . $callbackResponse->getMessage() . "\n";
}

// Example 4: Capture (Complete Sale after Authorization)
echo "\n=== Capture Example ===\n";
$captureResponse = $gateway->completeSale([
    'amount'        => '50.00',
    'currency'      => 'AZN',
    'transactionId' => 'AUTH12345',
    'rrn'           => '317276406077',
    'intRef'        => 'ABC123XYZ987',
])->send();

if ($captureResponse->isSuccessful()) {
    echo "Capture successful! Funds have been charged.\n";
} else {
    echo "Capture failed: " . $captureResponse->getMessage() . "\n";
}

// Example 5: Void Authorization
echo "\n=== Void Authorization Example ===\n";
$voidResponse = $gateway->void([
    'transactionId' => 'AUTH12345',
    'rrn'           => '317276406077',
    'intRef'        => 'ABC123XYZ987',
])->send();

if ($voidResponse->isSuccessful()) {
    echo "Authorization voided successfully!\n";
} else {
    echo "Void failed: " . $voidResponse->getMessage() . "\n";
}

// Example 6: Refund
echo "\n=== Refund Example ===\n";
$refundResponse = $gateway->refund([
    'amount'        => '10.00',
    'currency'      => 'AZN',
    'transactionId' => '12345',
    'rrn'           => '317276406077',
    'intRef'        => 'ABC123XYZ987',
])->send();

if ($refundResponse->isSuccessful()) {
    echo "Refund successful!\n";
} else {
    echo "Refund failed: " . $refundResponse->getMessage() . "\n";
}

// Example 7: Status Check
echo "\n=== Status Check Example ===\n";
$statusResponse = $gateway->status([
    'transactionId' => '12345',
    'rrn'           => '317276406077',
    'intRef'        => 'ABC123XYZ987',
])->send();

if ($statusResponse->isSuccessful()) {
    echo "Status check successful!\n";
    // Additional status information would be available in response
} else {
    echo "Status check failed: " . $statusResponse->getMessage() . "\n";
}

echo "\n=== Complete Authorization Flow Example ===\n";
echo "1. Create authorization -> 2. Handle callback -> 3. Capture or Void\n";
echo "This demonstrates the full pre-auth + capture workflow.\n";
