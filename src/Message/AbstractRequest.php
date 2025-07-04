<?php

namespace Omnipay\AzeriCard\Message;

use Omnipay\Common\Message\AbstractRequest as BaseRequest;

/**
 * Base request for all AzeriCard Omnipay requests.
 * Handles parameter accessors, signature, and validation.
 */
abstract class AbstractRequest extends BaseRequest
{
    /**
     * Get the terminal ID.
     *
     * @return string|null
     */
    public function getTerminalId()
    {
        return $this->getParameter('terminalId');
    }

    /**
     * Set the terminal ID.
     *
     * @param string $value
     * @return $this
     */
    public function setTerminalId($value)
    {
        return $this->setParameter('terminalId', $value);
    }

    /**
     * Get the private key file path.
     *
     * @return string|null
     */
    public function getPrivateKeyPath()
    {
        return $this->getParameter('privateKeyPath');
    }

    /**
     * Set the private key file path.
     *
     * @param string $value
     * @return $this
     */
    public function setPrivateKeyPath($value)
    {
        return $this->setParameter('privateKeyPath', $value);
    }

    /**
     * Get the public key file path.
     *
     * @return string|null
     */
    public function getPublicKeyPath()
    {
        return $this->getParameter('publicKeyPath');
    }

    /**
     * Set the public key file path.
     *
     * @param string $value
     * @return $this
     */
    public function setPublicKeyPath($value)
    {
        return $this->setParameter('publicKeyPath', $value);
    }

    /**
     * Get the order amount.
     *
     * @return string|null
     */
    public function getAmount()
    {
        return $this->getParameter('amount');
    }

    /**
     * Set the order amount.
     *
     * @param string $value
     * @return $this
     */
    public function setAmount($value)
    {
        return $this->setParameter('amount', $value);
    }

    /**
     * Get the currency code.
     *
     * @return string|null
     */
    public function getCurrency()
    {
        return $this->getParameter('currency');
    }

    /**
     * Set the currency code.
     *
     * @param string $value
     * @return $this
     */
    public function setCurrency($value)
    {
        return $this->setParameter('currency', $value);
    }

    /**
     * Get the transaction type (TRTYPE).
     *
     * @return string|null
     */
    public function getTrtype()
    {
        return $this->getParameter('trtype');
    }

    /**
     * Set the transaction type (TRTYPE).
     *
     * @param string $value
     * @return $this
     */
    public function setTrtype($value)
    {
        return $this->setParameter('trtype', $value);
    }

    /**
     * Get the transaction timestamp (YYYYMMDDHHMMSS, UTC).
     *
     * @return string|null
     */
    public function getTimestamp()
    {
        return $this->getParameter('timestamp');
    }

    /**
     * Set the transaction timestamp.
     *
     * @param string $value
     * @return $this
     */
    public function setTimestamp($value)
    {
        return $this->setParameter('timestamp', $value);
    }

    /**
     * Get the merchant nonce.
     *
     * @return string|null
     */
    public function getNonce()
    {
        return $this->getParameter('nonce');
    }

    /**
     * Set the merchant nonce.
     *
     * @param string $value
     * @return $this
     */
    public function setNonce($value)
    {
        return $this->setParameter('nonce', $value);
    }

    /**
     * Get the merchant URL.
     *
     * @return string|null
     */
    public function getMerchUrl()
    {
        return $this->getParameter('merchUrl');
    }

    /**
     * Set the merchant URL.
     *
     * @param string $value
     * @return $this
     */
    public function setMerchUrl($value)
    {
        return $this->setParameter('merchUrl', $value);
    }

    // --- Validation & helpers ---

    /**
     * Format amount to two decimal places.
     *
     * @param mixed $amount
     * @return string
     */
    protected function formatAmount($amount)
    {
        return number_format((float) $amount, 2, '.', '');
    }

    /**
     * Generate a timestamp in the format YmdHis (UTC).
     *
     * @return string
     */
    protected function generateTimestamp()
    {
        return gmdate('YmdHis');
    }

    /**
     * Generate a random nonce for cryptographic operations.
     *
     * @param int $length
     * @return string
     * @throws \InvalidArgumentException
     */
    protected function generateNonce($length = 16)
    {
        if ($length % 2 !== 0) {
            throw new \InvalidArgumentException('Nonce length must be even');
        }
        return bin2hex(random_bytes($length / 2));
    }

    /**
     * Validate presence of all required fields for requests.
     * Throws \InvalidArgumentException on first missing.
     *
     * @param array $fields
     */
    protected function validateRequiredFields(array $fields)
    {
        foreach ($fields as $field) {
            $value = $this->{'get' . ucfirst($field)}();
            if ($value === null || $value === '') {
                throw new \InvalidArgumentException("{$field} parameter is required");
            }
        }
    }

    /**
     * Sign the given fields using the private key.
     *
     * @param array $fields Field values in correct order
     * @return string
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    protected function sign(array $fields)
    {
        $privateKeyPath = $this->getPrivateKeyPath();
        $this->validatePrivateKey($privateKeyPath);

        $source     = $this->buildSignatureSource($fields);
        $privateKey = $this->loadPrivateKey($privateKeyPath);

        $result = openssl_sign($source, $signature, $privateKey, OPENSSL_ALGO_SHA256);

        if ($result === false) {
            throw new \RuntimeException('Failed to sign data: ' . openssl_error_string());
        }

        return bin2hex($signature);
    }

    /**
     * Build the signature source string from field values.
     *
     * @param array $fields
     * @return string
     */
    protected function buildSignatureSource(array $fields)
    {
        $source = '';
        foreach ($fields as $value) {
            $source .= strlen($value) . $value;
        }
        return $source;
    }

    /**
     * Validate that the private key file exists and is readable.
     *
     * @param string $privateKeyPath
     * @return void
     * @throws \InvalidArgumentException
     */
    protected function validatePrivateKey($privateKeyPath)
    {
        if (empty($privateKeyPath)) {
            throw new \InvalidArgumentException('Private key path not specified');
        }
        if (! file_exists($privateKeyPath)) {
            throw new \InvalidArgumentException('Private key file not found: ' . $privateKeyPath);
        }
        if (! is_readable($privateKeyPath)) {
            throw new \InvalidArgumentException('Private key file is not readable: ' . $privateKeyPath);
        }
    }

    /**
     * Load the private key content from file.
     *
     * @param string $privateKeyPath
     * @return string
     * @throws \RuntimeException
     */
    protected function loadPrivateKey($privateKeyPath)
    {
        $privateKey = file_get_contents($privateKeyPath);
        if ($privateKey === false) {
            throw new \RuntimeException('Failed to read private key file: ' . $privateKeyPath);
        }
        return $privateKey;
    }

    /**
     * Get the correct AzeriCard endpoint URL based on test mode.
     *
     * @return string
     */
    public function getEndpoint()
    {
        return $this->getTestMode()
        ? \Omnipay\AzeriCard\Constants::TEST_ENDPOINT
        : \Omnipay\AzeriCard\Constants::PRODUCTION_ENDPOINT;
    }

}
