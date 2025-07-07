<?php

namespace Omnipay\AzeriCard\Message\Requests;

use Omnipay\AzeriCard\Constants;
use Omnipay\Common\Message\AbstractRequest as OmnipayAbstractRequest;

/**
 * Base abstract request for AzeriCard Omnipay integration.
 */
abstract class AbstractRequest extends OmnipayAbstractRequest
{
    /**
     * Get the AzeriCard gateway endpoint based on test mode.
     *
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->getTestMode()
        ? Constants::TEST_ENDPOINT
        : Constants::PROD_ENDPOINT;
    }

    // ---- Required parameter accessors ----

    /**
     * Get Terminal ID.
     *
     * @return string|null
     */
    public function getTerminalId()
    {
        return $this->getParameter('terminalId');
    }

    /**
     * Set Terminal ID.
     *
     * @param string $value
     * @return $this
     */
    public function setTerminalId($value)
    {
        return $this->setParameter('terminalId', $value);
    }

    /**
     * Get private key file path.
     *
     * @return string|null
     */
    public function getPrivateKeyPath()
    {
        return $this->getParameter('privateKeyPath');
    }

    /**
     * Set private key file path.
     *
     * @param string $value
     * @return $this
     */
    public function setPrivateKeyPath($value)
    {
        return $this->setParameter('privateKeyPath', $value);
    }

    /**
     * Get public key file path.
     *
     * @return string|null
     */
    public function getPublicKeyPath()
    {
        return $this->getParameter('publicKeyPath');
    }

    /**
     * Set public key file path.
     *
     * @param string $value
     * @return $this
     */
    public function setPublicKeyPath($value)
    {
        return $this->setParameter('publicKeyPath', $value);
    }

    /**
     * Get merchant URL.
     *
     * @return string|null
     */
    public function getMerchUrl()
    {
        return $this->getParameter('merchUrl');
    }

    /**
     * Set merchant URL.
     *
     * @param string $value
     * @return $this
     */
    public function setMerchUrl($value)
    {
        return $this->setParameter('merchUrl', $value);
    }

    // ---- Optional documented parameter accessors ----

    /**
     * Get RRN identifier.
     *
     * @return string|null
     */
    public function getRRN()
    {
        return $this->getParameter('rrn');
    }

    /**
     * Set RRN identifier.
     *
     * @param string $value
     * @return $this
     */
    public function setRRN($value)
    {
        return $this->setParameter('rrn', $value);
    }

    /**
     * Get int_ref identifier.
     *
     * @return string|null
     */
    public function getIntRef()
    {
        return $this->getParameter('int_ref');
    }

    /**
     * Set int_ref identifier.
     *
     * @param string $value
     * @return $this
     */
    public function setIntRef($value)
    {
        return $this->setParameter('int_ref', $value);
    }

    /**
     * Get order identifier.
     *
     * @return string|null
     */
    public function getOrder()
    {
        return $this->getParameter('order');
    }

    /**
     * Set order identifier.
     *
     * @param string $value
     * @return $this
     */
    public function setOrder($value)
    {
        return $this->setParameter('order', $value);
    }

    /**
     * Get description.
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->getParameter('description');
    }

    /**
     * Set description.
     *
     * @param string $value
     * @return $this
     */
    public function setDescription($value)
    {
        return $this->setParameter('description', $value);
    }

    /**
     * Get email.
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->getParameter('email');
    }

    /**
     * Set email.
     *
     * @param string $value
     * @return $this
     */
    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
    }

    /**
     * Get customer name.
     *
     * @return string|null
     */
    public function getCustomerName()
    {
        return $this->getParameter('name');
    }

    /**
     * Set customer name.
     *
     * @param string $value
     * @return $this
     */
    public function setCustomerName($value)
    {
        return $this->setParameter('name', $value);
    }

    /**
     * Get merchant name.
     *
     * @return string|null
     */
    public function getMerchName()
    {
        return $this->getParameter('merchName');
    }

    /**
     * Set merchant name.
     *
     * @param string $value
     * @return $this
     */
    public function setMerchName($value)
    {
        return $this->setParameter('merchName', $value);
    }

    /**
     * Get country code.
     *
     * @return string|null
     */
    public function getCountry()
    {
        return $this->getParameter('country');
    }

    /**
     * Set country code.
     *
     * @param string $value
     * @return $this
     */
    public function setCountry($value)
    {
        return $this->setParameter('country', $value);
    }

    /**
     * Get merchant GMT.
     *
     * @return string|null
     */
    public function getMerchGmt()
    {
        return $this->getParameter('merchGmt');
    }

    /**
     * Set merchant GMT.
     *
     * @param string $value
     * @return $this
     */
    public function setMerchGmt($value)
    {
        return $this->setParameter('merchGmt', $value);
    }

    /**
     * Get language.
     *
     * @return string|null
     */
    public function getLang()
    {
        return $this->getParameter('lang');
    }

    /**
     * Set language.
     *
     * @param string $value
     * @return $this
     */
    public function setLang($value)
    {
        return $this->setParameter('lang', $value);
    }

    /**
     * Get merchant info (M_INFO).
     *
     * @return string|null
     */
    public function getMInfo()
    {
        return $this->getParameter('mInfo');
    }

    /**
     * Set merchant info (M_INFO).
     *
     * @param string $value
     * @return $this
     */
    public function setMInfo($value)
    {
        return $this->setParameter('mInfo', $value);
    }

    /**
     * Get return/callback URL (BACKREF).
     *
     * @return string|null
     */
    public function getReturnUrl()
    {
        return $this->getParameter('returnUrl');
    }

    /**
     * Set return/callback URL (BACKREF).
     *
     * @param string $value
     * @return $this
     */
    public function setReturnUrl($value)
    {
        return $this->setParameter('returnUrl', $value);
    }

    /**
     * Get timestamp.
     *
     * @return string|null
     */
    public function getTimestamp()
    {
        return $this->getParameter('timestamp');
    }

    /**
     * Set timestamp.
     *
     * @param string $value
     * @return $this
     */
    public function setTimestamp($value)
    {
        return $this->setParameter('timestamp', $value);
    }

    /**
     * Get nonce.
     *
     * @return string|null
     */
    public function getNonce()
    {
        return $this->getParameter('nonce');
    }

    /**
     * Set nonce.
     *
     * @param string $value
     * @return $this
     */
    public function setNonce($value)
    {
        return $this->setParameter('nonce', $value);
    }

    /**
     * Format amount to two decimal places, string.
     *
     * @param mixed $amount
     * @return string
     */
    protected function formatAmount($amount): string
    {
        return number_format((float) $amount, 2, '.', '');
    }

    /**
     * Generate timestamp in 'YmdHis' (UTC).
     * @return string
     */
    protected function generateTimestamp(): string
    {
        return gmdate('YmdHis');
    }

    /**
     * Generate a random hexadecimal nonce.
     * @param int $length
     * @return string
     */
    protected function generateNonce($length = 16): string
    {
        if ($length % 2 !== 0) {
            throw new \InvalidArgumentException('Nonce length must be even');
        }
        return bin2hex(random_bytes($length / 2));
    }

    /**
     * Validate that required fields are set and non-empty.
     *
     * @param array $fields
     * @return void
     * @throws \InvalidArgumentException
     */
    protected function validateRequiredFields(array $fields)
    {
        foreach ($fields as $field) {
            $getter = 'get' . ucfirst($field);
            $value  = method_exists($this, $getter)
            ? $this->{$getter}()
            : $this->getParameter($field);
            if ($value === null || $value === '') {
                throw new \InvalidArgumentException("The {$field} parameter is required");
            }
        }
    }

    /**
     * Build P_SIGN signature according to AzeriCard protocol.
     * Pass fields in the exact order and value (use '-' for missing).
     *
     * @param array $fields Field values in order
     * @return string
     * @throws \InvalidArgumentException|\RuntimeException
     */
    protected function sign(array $fields): string
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
     * Build signature source string: len+value (or just '-' for missing), no length for '-'.
     * @param array $fields
     * @return string
     */
    protected function buildSignatureSource(array $fields): string
    {
        $source = '';
        foreach ($fields as $value) {
            if ($value === null || $value === '') {
                $source .= '-';
            } else {
                $source .= strlen((string) $value) . $value;
            }
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
    protected function loadPrivateKey($privateKeyPath): string
    {
        $privateKey = file_get_contents($privateKeyPath);
        if ($privateKey === false) {
            throw new \RuntimeException('Failed to read private key file: ' . $privateKeyPath);
        }
        return $privateKey;
    }
}
