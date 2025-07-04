<?php

namespace Omnipay\AzeriCard\Message;

use Omnipay\Common\Message\AbstractResponse;

class CompletePurchaseResponse extends AbstractResponse
{
    /**
     * Check if the purchase completion was successful.
     *
     * @return bool True if successful
     */
    public function isSuccessful()
    {
        return isset($this->data['ACTION']) && $this->data['ACTION'] === '0';
    }

    /**
     * Get the transaction reference.
     *
     * @return string|null The transaction reference
     */
    public function getTransactionReference()
    {
        return $this->data['INT_REF'] ?? null;
    }

    /**
     * Get the response message.
     *
     * @return string The response message
     */
    public function getMessage()
    {
        if (! $this->isSuccessful()) {
            return $this->data['DESC'] ?? 'Transaction failed';
        }
        return $this->data['DESC'] ?? 'Transaction successful';
    }

    /**
     * Get the response code.
     *
     * @return string|null The response code
     */
    public function getCode()
    {
        return $this->data['ACTION'] ?? null;
    }

    /**
     * Get the RRN (Retrieval Reference Number).
     *
     * @return string|null The RRN
     */
    public function getRRN()
    {
        return $this->data['RRN'] ?? null;
    }
}
