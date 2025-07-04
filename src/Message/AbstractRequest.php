<?php

namespace Omnipay\AzeriCard\Message;

use Omnipay\Common\Message\AbstractRequest as BaseRequest;

/**
 * Base request class for all AzeriCard Omnipay requests.
 *
 * Handles signature generation, field validation, and common parameter accessors.
 */
abstract class AbstractRequest extends BaseRequest
{
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
     * @param int $length The length of the nonce (must be even)
     * @return string Hexadecimal representation of the generated nonce
     * @throws \InvalidArgumentException When length is not even
     */
    protected function generateNonce($length = 16)
    {
        if ($length % 2 !== 0) {
            throw new \InvalidArgumentException('Nonce length must be even');
        }
        return bin2hex(random_bytes($length / 2));
    }

    /**
     * Sign the given fields using the private key.
     *
     * @param array $fields Array of field values to sign (must be ordered)
     * @return string Hexadecimal representation of the signature
     * @throws \InvalidArgumentException When private key validation fails
     * @throws \RuntimeException When signing fails
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
     * Validate that the private key file exists and is readable.
     *
     * @param string $privateKeyPath Path to the private key file
     * @return void
     * @throws \InvalidArgumentException When validation fails
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
     * @param string $privateKeyPath Path to the private key file
     * @return string The private key content
     * @throws \RuntimeException When file reading fails
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
     * Build the signature source string from field values.
     *
     * @param array $fields Array of field values
     * @return string The concatenated signature source
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
     * Format amount to two decimal places.
     *
     * @param mixed $amount The amount to format
     * @return string Formatted amount with 2 decimal places
     */
    protected function formatAmount($amount)
    {
        return number_format((float) $amount, 2, '.', '');
    }

    /**
     * Validate that ORDER field is numeric.
     *
     * @return void
     * @throws \InvalidArgumentException When ORDER field is not numeric
     */
    protected function validateOrderField()
    {
        $order = $this->getOrder();
        if ($order && ! is_numeric($order)) {
            throw new \InvalidArgumentException('ORDER field must be numeric');
        }
    }

    /**
     * Validate field lengths according to AzeriCard specifications.
     *
     * @return void
     * @throws \InvalidArgumentException When field length validation fails
     */
    protected function validateFieldLengths()
    {
        $validations = [
            'amount'      => ['max' => 12, 'name' => 'AMOUNT'],
            'currency'    => ['max' => 3, 'min' => 3, 'name' => 'CURRENCY'],
            'order'       => ['max' => 32, 'min' => 6, 'name' => 'ORDER'],
            'description' => ['max' => 50, 'min' => 1, 'name' => 'DESC'],
            'merchName'   => ['max' => 50, 'min' => 1, 'name' => 'MERCH_NAME'],
            'merchUrl'    => ['max' => 250, 'min' => 1, 'name' => 'MERCH_URL'],
            'email'       => ['max' => 80, 'name' => 'EMAIL'],
            'trtype'      => ['max' => 1, 'name' => 'TRTYPE'],
            'country'     => ['max' => 2, 'min' => 2, 'name' => 'COUNTRY'],
            'merchGmt'    => ['max' => 5, 'min' => 1, 'name' => 'MERCH_GMT'],
            'returnUrl'   => ['max' => 250, 'min' => 1, 'name' => 'BACKREF'],
            'timestamp'   => ['max' => 14, 'min' => 14, 'name' => 'TIMESTAMP'],
            'nonce'       => ['max' => 64, 'min' => 1, 'name' => 'NONCE'],
            'lang'        => ['max' => 2, 'min' => 2, 'name' => 'LANG'],
            'pSign'       => ['max' => 256, 'min' => 1, 'name' => 'P_SIGN'],
            'name'        => ['max' => 45, 'min' => 2, 'name' => 'NAME'],
            'mInfo'       => ['max' => 35000, 'name' => 'M_INFO'],
        ];

        foreach ($validations as $field => $rules) {
            $value = $this->getParameter($field);
            if ($value !== null) {
                $length = strlen($value);

                if (isset($rules['min']) && $length < $rules['min']) {
                    throw new \InvalidArgumentException(
                        sprintf('%s must be at least %d characters long', $rules['name'], $rules['min'])
                    );
                }
                if (isset($rules['max']) && $length > $rules['max']) {
                    throw new \InvalidArgumentException(
                        sprintf('%s must not exceed %d characters', $rules['name'], $rules['max'])
                    );
                }
            }
        }
    }

    // -------------------------------------------------------------------------
    // Setters/getters for request-level parameters (bottom for style/clarity)
    // -------------------------------------------------------------------------

    /**
     * Get the order/transaction identifier.
     *
     * @return string|null
     */
    public function getOrder()
    {
        return $this->getParameter('order') ?: $this->getTransactionId();
    }

    /**
     * Set the order/transaction identifier.
     *
     * @param string $value
     * @return $this
     */
    public function setOrder($value)
    {
        return $this->setParameter('order', $value);
    }

    /**
     * Get the amount.
     *
     * @return string|null
     */
    public function getAmount()
    {
        return $this->getParameter('amount');
    }

    /**
     * Set the amount.
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
     * Get the transaction description.
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->getParameter('description');
    }

    /**
     * Set the transaction description.
     *
     * @param string $value
     * @return $this
     */
    public function setDescription($value)
    {
        return $this->setParameter('description', $value);
    }

    /**
     * Get the return/callback URL.
     *
     * @return string|null
     */
    public function getReturnUrl()
    {
        return $this->getParameter('returnUrl');
    }

    /**
     * Set the return/callback URL.
     *
     * @param string $value
     * @return $this
     */
    public function setReturnUrl($value)
    {
        return $this->setParameter('returnUrl', $value);
    }

    /**
     * Get the merchant name.
     *
     * @return string|null
     */
    public function getMerchName()
    {
        return $this->getParameter('merchName');
    }

    /**
     * Set the merchant name.
     *
     * @param string $value
     * @return $this
     */
    public function setMerchName($value)
    {
        return $this->setParameter('merchName', $value);
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

    /**
     * Get the merchant email address.
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->getParameter('email');
    }

    /**
     * Set the merchant email address.
     *
     * @param string $value
     * @return $this
     */
    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
    }

    /**
     * Get the country code.
     *
     * @return string|null
     */
    public function getCountry()
    {
        return $this->getParameter('country');
    }

    /**
     * Set the country code.
     *
     * @param string $value
     * @return $this
     */
    public function setCountry($value)
    {
        return $this->setParameter('country', $value);
    }

    /**
     * Get the merchant GMT offset.
     *
     * @return string|null
     */
    public function getMerchGmt()
    {
        return $this->getParameter('merchGmt');
    }

    /**
     * Set the merchant GMT offset.
     *
     * @param string $value
     * @return $this
     */
    public function setMerchGmt($value)
    {
        return $this->setParameter('merchGmt', $value);
    }

    /**
     * Get the language code.
     *
     * @return string|null
     */
    public function getLang()
    {
        return $this->getParameter('lang');
    }

    /**
     * Set the language code.
     *
     * @param string $value
     * @return $this
     */
    public function setLang($value)
    {
        return $this->setParameter('lang', $value);
    }

    /**
     * Get the customer's full name.
     *
     * @return string|null
     */
    public function getCustomerName()
    {
        return $this->getParameter('name');
    }

    /**
     * Set the customer's full name.
     *
     * @param string $value
     * @return $this
     */
    public function setCustomerName($value)
    {
        return $this->setParameter('name', $value);
    }

    /**
     * Get the additional info (M_INFO) parameter.
     *
     * @return string|null
     */
    public function getMInfo()
    {
        return $this->getParameter('mInfo');
    }

    /**
     * Set the additional info (M_INFO) parameter.
     *
     * @param string $value
     * @return $this
     */
    public function setMInfo($value)
    {
        return $this->setParameter('mInfo', $value);
    }
}
