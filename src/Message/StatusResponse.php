<?php

namespace Omnipay\AzeriCard\Message;

use Omnipay\Common\Message\AbstractResponse;

class StatusResponse extends AbstractResponse
{
    /**
     * Check if the status request was successful.
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
            return $this->data['DESC'] ?? 'Status check failed';
        }
        return $this->data['DESC'] ?? 'Status check successful';
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

    /**
     * Get the transaction status.
     *
     * @return string|null The transaction status
     */
    public function getTransactionStatus()
    {
        return $this->data['RESULT'] ?? null;
    }

    /**
     * Get the approval code.
     *
     * @return string|null The approval code
     */
    public function getApprovalCode()
    {
        return $this->data['APPROVAL'] ?? null;
    }

    /**
     * Get the amount.
     *
     * @return string|null The amount
     */
    public function getAmount()
    {
        return $this->data['AMOUNT'] ?? null;
    }
}
