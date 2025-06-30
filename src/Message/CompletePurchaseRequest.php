<?php

namespace Omnipay\AzeriCard\Message;

class CompletePurchaseRequest extends AbstractRequest
{
    /**
     * Get the request data from HTTP callback.
     *
     * @return array The callback data
     * @throws \InvalidArgumentException When validation fails
     * @throws \RuntimeException When signature verification fails
     */
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

    /**
     * Send the data and create response.
     *
     * @param array $data The request data
     * @return CompletePurchaseResponse
     */
    public function sendData($data)
    {
        return $this->response = new CompletePurchaseResponse($this, $data);
    }

    /**
     * Validate that required callback fields are present.
     *
     * @param array $data The callback data
     * @return void
     * @throws \InvalidArgumentException When required fields are missing
     */
    protected function validateCallbackData(array $data)
    {
        $required = ['ACTION', 'ORDER', 'INT_REF'];
        foreach ($required as $field) {
            if (! isset($data[$field])) {
                throw new \InvalidArgumentException("Missing required callback field: {$field}");
            }
        }
    }

    /**
     * Verify the signature of callback data.
     *
     * @param array $data The callback data
     * @return bool True if signature is valid
     * @throws \InvalidArgumentException When signature validation fails
     * @throws \RuntimeException When signature verification fails
     */
    protected function verifySignature(array $data)
    {
        $publicKeyPath = $this->getParameter('publicKeyPath');
        if (! file_exists($publicKeyPath)) {
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
