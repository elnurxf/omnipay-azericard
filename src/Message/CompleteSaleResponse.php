<?php

namespace Omnipay\AzeriCard\Message;

use Omnipay\Common\Message\AbstractResponse;

class CompleteSaleResponse extends AbstractResponse
{
    /**
     * Check if the complete sale request was successful.
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
            return $this->data['DESC'] ?? 'Sale completion failed';
        }
        return $this->data['DESC'] ?? 'Sale completed successfully';
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

    /**
     * Get the card number (masked).
     *
     * @return string|null The card number
     */
    public function getCardNumber()
    {
        return $this->data['CARD'] ?? null;
    }

    /**
     * Get the currency code.
     *
     * @return string|null The ISO currency code
     */
    public function getCurrency()
    {
        return $this->data['CURRENCY'] ?? null;
    }

    /**
     * Get the order.
     *
     * @return string|null The order
     */
    public function getOrder()
    {
        return $this->data['ORDER'] ?? null;
    }

    /**
     * Get the timestamp.
     *
     * @return string|null The timestamp in format YmdHis
     */
    public function getTimestamp()
    {
        return $this->data['TIMESTAMP'] ?? null;
    }

    /**
     * Get the transaction type.
     *
     * @return string|null The transaction type
     */
    public function getTransactionType()
    {
        return $this->data['TRTYPE'] ?? null;
    }

    /**
     * Get the terminal ID.
     *
     * @return string|null The terminal ID
     */
    public function getTerminal()
    {
        return $this->data['TERMINAL'] ?? null;
    }

    /**
     * Get the RC (response code).
     *
     * @return string|null The RC code
     */
    public function getRc()
    {
        return $this->data['RC'] ?? null;
    }

    /**
     * Get the token if provided.
     *
     * @return string|null The token value
     */
    public function getToken()
    {
        return $this->data['TOKEN'] ?? null;
    }

    /**
     * Get the nonce value.
     *
     * @return string|null The nonce value
     */
    public function getNonce()
    {
        return $this->data['NONCE'] ?? null;
    }

    /**
     * Get the Installment/3D-Secure data.
     *
     * @return string|null Raw INSTALL field content
     */
    public function getInstallData()
    {
        return $this->data['INSTALL'] ?? null;
    }

    /**
     * Get the signature from the response.
     *
     * @return string|null Hexadecimal signature
     */
    public function getSignature()
    {
        return $this->data['P_SIGN'] ?? null;
    }
}
