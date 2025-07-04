<?php

require_once '../vendor/autoload.php';
require_once 'config.php';

use Omnipay\Omnipay;

// Create the gateway instance directly
$gateway = Omnipay::create('AzeriCard');

// Configure the gateway
$gateway->setTerminalId($azericard_terminal);
$gateway->setPrivateKeyPath($azericard_private_key_path);
$gateway->setPublicKeyPath($azericard_public_key_path);
$gateway->setTestMode(true); // or false for production

// Authorization Request (Pre-Auth)
try {

    $authResponse = $gateway->authorize([
        'amount'      => '50.00',
        'currency'    => 'AZN',
        'order'       => '123456',
        'merchUrl'    => 'https://yoursite.com/auth-callback',
        'description' => 'Authorization for Order #12345',
        'email'       => 'customer@example.com',
        'name'        => 'John Doe',
    ])->send();

    if ($authResponse->isRedirect()) {
        $authResponse->redirect();

        //echo "Redirecting to AzeriCard for authorization...\n";
        //echo "Redirect URL: " . $authResponse->getRedirectUrl() . "\n";
        // In real application: $authResponse->redirect();
    } else {
        echo "Authorization Error: " . $authResponse->getMessage() . "\n";
    }
} catch (Exception $e) {
    echo "Authorization Error: " . $e->getMessage() . "\n";
}
