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

// Status Check
try {

    $statusResponse = $gateway->status([
        'order' => '123457',
    ])->send();

    if ($statusResponse->isSuccessful()) {
        echo "Status check successful! Result:\n";
        print_r($statusResponse->getResult());
    } else {
        echo "Status check failed: " . $statusResponse->getMessage() . "\n";
    }

} catch (Exception $e) {
    echo "Authorization Error: " . $e->getMessage() . "\n";
}
