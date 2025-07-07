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
try {
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
} catch (Exception $e) {
    echo "Purchase Error: " . $e->getMessage() . "\n";
}

// Example 2: Authorization Request (Pre-Auth)
echo "\n=== Authorization Example ===\n";
try {
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
} catch (Exception $e) {
    echo "Authorization Error: " . $e->getMessage() . "\n";
}

// Example 3: Handle Callback (Complete Purchase/Authorization)
echo "\n=== Callback Handler Example ===\n";
try {
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
} catch (Exception $e) {
    echo "Callback Error: " . $e->getMessage() . "\n";
}

// Example 4: Capture (Complete Sale after Authorization)
echo "\n=== Capture Example ===\n";
try {
    $captureResponse = $gateway->completeSale([
        'amount'        => '50.00',
        'currency'      => 'AZN',
        'transactionId' => 'AUTH12345',
        'rrn'           => '317276406077',
        'INT_REF'       => 'ABC123XYZ987',
    ])->send();

    if ($captureResponse->isSuccessful()) {
        echo "Capture successful!\n";
    } else {
        echo "Capture failed: " . $captureResponse->getMessage() . "\n";
    }
} catch (Exception $e) {
    echo "Capture Error: " . $e->getMessage() . "\n";
}

// Example 5: Refund Request
echo "\n=== Refund Example ===\n";
try {
    $refundResponse = $gateway->refund([
        'amount'        => '20.00',
        'currency'      => 'AZN',
        'transactionId' => '12345',
        'rrn'           => '317276406077',
        'INT_REF'       => 'ABC123XYZ987',
    ])->send();

    if ($refundResponse->isSuccessful()) {
        echo "Refund successful!\n";
    } else {
        echo "Refund failed: " . $refundResponse->getMessage() . "\n";
    }
} catch (Exception $e) {
    echo "Refund Error: " . $e->getMessage() . "\n";
}

// Example 6: Status Check
echo "\n=== Status Check Example ===\n";
try {
    $statusResponse = $gateway->status([
        'transactionId' => '12345',
        'rrn'           => '317276406077',
        'INT_REF'       => 'ABC123XYZ987',
    ])->send();

    if ($statusResponse->isSuccessful()) {
        echo "Status check successful! Status: " . $statusResponse->getAction() . "\n";
    } else {
        echo "Status check failed: " . $statusResponse->getMessage() . "\n";
    }
} catch (Exception $e) {
    echo "Status Error: " . $e->getMessage() . "\n";
}
