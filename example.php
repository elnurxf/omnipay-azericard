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

// Example 1: Purchase Request
$purchaseResponse = $gateway->purchase([
    'amount'        => '20.00',
    'currency'      => 'AZN',
    'transactionId' => 'ORDER12345',
    'returnUrl'     => 'https://yoursite.com/callback',
    'description'   => 'Order #12345',
    'email'         => 'customer@example.com',
    'name'          => 'John Doe',
])->send();

if ($purchaseResponse->isRedirect()) {
    // Redirect user to AzeriCard payment page
    $purchaseResponse->redirect();
} else {
    echo "Error: " . $purchaseResponse->getMessage();
}

// Example 2: Handle Callback (Complete Purchase)
$callbackResponse = $gateway->completePurchase()->send();

if ($callbackResponse->isSuccessful()) {
    $reference = $callbackResponse->getTransactionReference();
    $rrn       = $callbackResponse->getRRN();
    echo "Payment successful! Reference: {$reference}, RRN: {$rrn}";
} else {
    echo "Payment failed: " . $callbackResponse->getMessage();
}

// Example 3: Refund
$refundResponse = $gateway->refund([
    'amount'        => '10.00',
    'transactionId' => 'ORDER12345',
    'rrn'           => '317276406077',
    'intRef'        => 'ABC123XYZ987',
])->send();

if ($refundResponse->isSuccessful()) {
    echo "Refund successful!";
} else {
    echo "Refund failed: " . $refundResponse->getMessage();
}
