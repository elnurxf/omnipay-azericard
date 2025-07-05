<?php

namespace Omnipay\AzeriCard\Message\Responses;

use Omnipay\Common\Message\AbstractResponse;

/**
 * AzeriCard completion response handler.
 */
class CompletePurchaseResponse extends AbstractResponse
{
    /**
     * Was the transaction successful?
     *
     * @return bool
     */
    public function isSuccessful()
    {
        return (isset($this->data['ACTION']) && (string) $this->data['ACTION'] === '0');
    }

    /**
     * Get the transaction reference (INTREF).
     *
     * @return string|null
     */
    public function getTransactionReference()
    {
        return $this->data['INTREF'] ?? null;
    }

    /**
     * Get the response message.
     *
     * @return string|null
     */
    public function getMessage()
    {
        return $this->data['DESC'] ?? null;
    }

    /**
     * Get the response code (ACTION).
     *
     * @return string|null
     */
    public function getCode()
    {
        return $this->data['ACTION'] ?? null;
    }

    /**
     * Get the RRN (Retrieval Reference Number).
     *
     * @return string|null
     */
    public function getRRN()
    {
        return $this->data['RRN'] ?? null;
    }

    /**
     * Get the approval code.
     *
     * @return string|null
     */
    public function getApproval()
    {
        return $this->data['APPROVAL'] ?? null;
    }

    /**
     * Get the order ID.
     *
     * @return string|null
     */
    public function getOrder()
    {
        return $this->data['ORDER'] ?? null;
    }
}
