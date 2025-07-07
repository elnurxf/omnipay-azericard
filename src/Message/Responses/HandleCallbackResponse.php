<?php

namespace Omnipay\AzeriCard\Message\Responses;

use Omnipay\Common\Message\AbstractResponse;

/**
 * AzeriCard completion response handler.
 */
class HandleCallbackResponse extends AbstractResponse
{
    /**
     * Was the transaction successful?
     */
    public function isSuccessful()
    {
        return (
            isset($this->data['ACTION'], $this->data['RC'])
            && (string) $this->data['ACTION'] === '0'
            && (string) $this->data['RC'] === '00'
        );
    }

    /**
     * Get the terminal ID.
     */
    public function getTerminal()
    {
        return $this->data['TERMINAL'] ?? null;
    }

    /**
     * Get the transaction type.
     */
    public function getTrType()
    {
        return $this->data['TRTYPE'] ?? null;
    }

    /**
     * Get the order ID.
     */
    public function getOrder()
    {
        return $this->data['ORDER'] ?? null;
    }

    /**
     * Get the amount.
     */
    public function getAmount()
    {
        return $this->data['AMOUNT'] ?? null;
    }

    /**
     * Get the currency.
     */
    public function getCurrency()
    {
        return $this->data['CURRENCY'] ?? null;
    }

    /**
     * Get the response code (ACTION).
     */
    public function getAction()
    {
        return $this->data['ACTION'] ?? null;
    }

    /**
     * Get the reason code (RC).
     */
    public function getRC()
    {
        return $this->data['RC'] ?? null;
    }

    /**
     * Get the approval code.
     */
    public function getApproval()
    {
        return $this->data['APPROVAL'] ?? null;
    }

    /**
     * Get the RRN (Retrieval Reference Number).
     */
    public function getRRN()
    {
        return $this->data['RRN'] ?? null;
    }

    /**
     * Get the transaction reference (INT_REF).
     */
    public function getIntRef()
    {
        return $this->data['INT_REF'] ?? null;
    }

    /**
     * Get the timestamp.
     */
    public function getTimestamp()
    {
        return $this->data['TIMESTAMP'] ?? null;
    }

    /**
     * Get the nonce.
     */
    public function getNonce()
    {
        return $this->data['NONCE'] ?? null;
    }

    /**
     * Get the signature (P_SIGN).
     */
    public function getPSign()
    {
        return $this->data['P_SIGN'] ?? null;
    }

    /**
     * Get the customer email.
     */
    public function getEmail()
    {
        return $this->data['EMAIL'] ?? null;
    }

    /**
     * Get the masked card number.
     */
    public function getMaskedCard()
    {
        return $this->data['MASKED_CARD'] ?? null;
    }

    /**
     * Get all callback data.
     */
    public function getCallbackData()
    {
        return $this->data ?? null;
    }
}
