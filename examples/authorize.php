<?php

require_once '../vendor/autoload.php';

use Omnipay\Omnipay;

// Create the gateway instance directly
$gateway = Omnipay::create('AzeriCard');

// Configure the gateway
$gateway->setTerminalId('YOUR_TERMINAL_ID');
$gateway->setPrivateKeyPath('/path/to/your/private.pem');
$gateway->setPublicKeyPath('/path/to/your/public.pem');
$gateway->setTestMode(true); // or false for production

// Example 2: Authorization Request (Pre-Auth)
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

