<?php

namespace Omnipay\AzeriCard\Message;

use Omnipay\Common\Message\AbstractRequest as BaseRequest;

// Added field validation methods to the AbstractRequest class.
abstract class AbstractRequest extends BaseRequest
{
    /**
     * Generate a timestamp in the format YmdHis.
     *
     * @return string The generated timestamp in GMT (UTC) time
     */
    protected function generateTimestamp()
    {
        $date = new \DateTime('now', new \DateTimeZone('UTC'));
        return $date->format('YmdHis');
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
     * @param array $fields Array of field values to sign
     * @return string Hexadecimal representation of the signature
     * @throws \InvalidArgumentException When private key validation fails
     * @throws \RuntimeException When signing fails
     */
    protected function sign(array $fields)
    {
        $privateKeyPath = $this->getParameter('privateKeyPath');
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
            $source .= strlen((string) $value) . $value;
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
     * Validate that ORDER field is numeric and meets requirements.
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
     * Generate a secure nonce with proper length validation.
     *
     * @param int $length The length of the nonce (must be between 8 and 32, and even)
     * @return string Hexadecimal representation of the generated nonce
     * @throws \InvalidArgumentException When length is invalid
     */
    protected function generateSecureNonce($length = 16)
    {
        if ($length < 8 || $length > 32) {
            throw new \InvalidArgumentException('Nonce length must be between 8 and 32 characters');
        }

        if ($length % 2 !== 0) {
            throw new \InvalidArgumentException('Nonce length must be even');
        }

        return bin2hex(random_bytes($length / 2));
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

    /**
     * Get the API endpoint URL based on test mode.
     *
     * @return string The endpoint URL
     */
    public function getEndpoint()
    {
        return $this->getTestMode()
        ? 'https://testmpi.3dsecure.az/cgi-bin/cgi_link'
        : 'https://mpi.3dsecure.az/cgi-bin/cgi_link';
    }

    /**
     * Get the private key file path.
     *
     * @return string|null The private key file path
     */
    public function getPrivateKeyPath()
    {
        return $this->getParameter('privateKeyPath');
    }

    /**
     * Get the public key file path.
     *
     * @return string|null The public key file path
     */
    public function getPublicKeyPath()
    {
        return $this->getParameter('publicKeyPath');
    }

    /**
     * Get the terminal ID.
     *
     * @return string|null The terminal ID
     */
    public function getTerminalId()
    {
        return $this->getParameter('terminalId');
    }

    /**
     * Get the merchant information.
     *
     * @return string|null The merchant information
     */
    public function getMInfo()
    {
        return $this->getParameter('mInfo');
    }

    /**
     * Set the merchant information.
     *
     * @param string $value The merchant information
     * @return $this
     */
    public function setMInfo($value)
    {
        return $this->setParameter('mInfo', $value);
    }

    /**
     * Get the order/transaction identifier.
     *
     * @return string|null The order identifier
     */
    public function getOrder()
    {
        return $this->getParameter('order') ?: $this->getTransactionId();
    }

    /**
     * Set the order identifier.
     *
     * @param string $value The order identifier
     * @return $this
     */
    public function setOrder($value)
    {
        return $this->setParameter('order', $value);
    }

    /**
     * Get the timestamp.
     *
     * @return string|null The timestamp
     */
    public function getTimestamp()
    {
        return $this->getParameter('timestamp');
    }

    /**
     * Set the timestamp.
     *
     * @param string $value The timestamp
     * @return $this
     */
    public function setTimestamp($value)
    {
        return $this->setParameter('timestamp', $value);
    }

    /**
     * Get the nonce.
     *
     * @return string|null The nonce
     */
    public function getNonce()
    {
        return $this->getParameter('nonce');
    }

    /**
     * Set the nonce.
     *
     * @param string $value The nonce
     * @return $this
     */
    public function setNonce($value)
    {
        return $this->setParameter('nonce', $value);
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
     * Get the transaction type.
     *
     * @return string|null
     */
    public function getTrtype()
    {
        return $this->getParameter('trtype');
    }

    /**
     * Set the transaction type.
     *
     * @param string $value
     * @return $this
     */
    public function setTrtype($value)
    {
        return $this->setParameter('trtype', $value);
    }

    /**
     * Get the RRN (Retrieval Reference Number).
     *
     * @return string|null The RRN
     */
    public function getRRN()
    {
        return $this->getParameter('rrn');
    }

    /**
     * Set the RRN (Retrieval Reference Number).
     *
     * @param string $value The RRN
     * @return $this
     */
    public function setRRN($value)
    {
        return $this->setParameter('rrn', $value);
    }

    /**
     * Get the internal reference number.
     *
     * @return string|null The internal reference number
     */
    public function getIntRef()
    {
        return $this->getParameter('intRef');
    }

    /**
     * Set the internal reference number.
     *
     * @param string $value The internal reference number
     * @return $this
     */
    public function setIntRef($value)
    {
        return $this->setParameter('intRef', $value);
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
}
