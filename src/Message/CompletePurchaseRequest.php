<?php

namespace Omnipay\AzeriCard\Message;

use Omnipay\AzeriCard\Constants;

class CompletePurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $data = $this->httpRequest->request->all();

        // Validate required fields
        $this->validateCallbackData($data);

        // Verify signature if public key is available
        if ($this->getParameter('publicKeyPath')) {
            $this->verifySignature($data);
        }

        return $data;
    }

    public function sendData($data)
    {
        return $this->response = new CompletePurchaseResponse($this, $data);
    }

    protected function validateCallbackData(array $data)
    {
        $required = ['ACTION', 'ORDER', 'INT_REF'];
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                throw new \InvalidArgumentException("Missing required callback field: {$field}");
            }
        }
    }

    protected function verifySignature(array $data)
    {
        $publicKeyPath = $this->getParameter('publicKeyPath');
        if (!file_exists($publicKeyPath)) {
            throw new \InvalidArgumentException('Public key file not found');
        }

        $signature = $data['P_SIGN'] ?? '';
        if (empty($signature)) {
            throw new \InvalidArgumentException('Missing signature in callback data');
        }

        $fields = [
            $data['AMOUNT'] ?? '-',
            $data['TERMINAL'] ?? '-',
            $data['APPROVAL'] ?? '-',
            $data['RRN'] ?? '-',
            $data['INT_REF'] ?? '-',
        ];

        $source = '';
        foreach ($fields as $field) {
            if ($field === '-') {
                $source .= '-';
            } else {
                $source .= strlen($field) . $field;
            }
        }

        $signature = hex2bin($data['P_SIGN']);
        if ($signature === false) {
            throw new \InvalidArgumentException('Invalid signature format');
        }

        $publicKey = file_get_contents($publicKeyPath);
        if ($publicKey === false) {
            throw new \RuntimeException('Failed to read public key file');
        }

        $result = openssl_verify($source, $signature, $publicKey, OPENSSL_ALGO_SHA256);
        if ($result !== 1) {
            throw new \RuntimeException('Invalid signature');
        }

        return true;
    }
}