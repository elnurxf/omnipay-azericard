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

// Purchase Request
try {

    $authResponse = $gateway->purchase([
        'amount'      => '10.00',
        'currency'    => 'AZN',
        'order'       => '123457',
        'merchUrl'    => 'https://yoursite.com/auth-callback',
        'description' => 'Purchase for Order #123457',
        'email'       => 'customer@example.com',
        'name'        => 'John Doe',
    ])->send();

    if ($authResponse->isRedirect()) {
        $authResponse->redirect();

        //echo "Redirecting to AzeriCard for purchase...\n";
        //echo "Redirect URL: " . $authResponse->getRedirectUrl() . "\n";
        // In real application: $authResponse->redirect();
    } else {
        echo "Purchase Error: " . $authResponse->getResult() . "\n";
    }
} catch (Exception $e) {
    echo "Purchase Error: " . $e->getMessage() . "\n";
}
