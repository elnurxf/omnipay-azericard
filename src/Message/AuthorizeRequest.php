<?php

namespace Omnipay\AzeriCard\Message;

use Omnipay\AzeriCard\Constants;

class AuthorizeRequest extends AbstractRequest
{
    /**
     * Get the authorization request data.
     *
     * @return array The request data
     * @throws \InvalidArgumentException When validation fails
     */
    public function getData()
    {
        $this->validate('amount', 'terminalId');
        $this->validateAuthorizationSpecificFields();

        $timestamp   = $this->getTimestamp() ?: $this->generateTimestamp();
        $nonce       = $this->getNonce() ?: $this->generateNonce();
        $amount      = $this->formatAmount($this->getAmount());
        $order       = $this->getOrder() ?: $this->getTransactionId();
        $currency    = $this->getCurrency() ?: Constants::CURRENCY_AZN;
        $description = $this->getDescription() ?: 'Authorization';
        $terminal    = $this->getTerminalId();

        $data = [
            Constants::FIELD_AMOUNT        => $amount,
            Constants::FIELD_CURRENCY      => $currency,
            Constants::FIELD_ORDER         => $order,
            Constants::FIELD_DESC          => $description,
            Constants::FIELD_TERMINAL      => $terminal,
            Constants::FIELD_TRTYPE        => Constants::TRTYPE_PRE_AUTH,
            Constants::FIELD_TIMESTAMP     => $timestamp,
            Constants::FIELD_NONCE         => $nonce,
            Constants::FIELD_MAC_KEY_INDEX => 0,
        ];

        // Add optional fields if provided
        $this->addOptionalFields($data);

        // Generate signature - order is critical for AzeriCard
        $data[Constants::FIELD_P_SIGN] = $this->sign([
            $amount,
            $currency,
            $terminal,
            Constants::TRTYPE_PRE_AUTH,
            $timestamp,
            $nonce,
        ]);

        return $data;
    }

    /**
     * Add optional fields to the data array.
     *
     * @param array &$data The data array to modify
     * @return void
     */
    protected function addOptionalFields(array &$data)
    {
        $optionalFields = [
            'BACKREF'    => $this->getReturnUrl(),
            'MERCH_NAME' => $this->getMerchName(),
            'MERCH_URL'  => $this->getMerchUrl(),
            'EMAIL'      => $this->getEmail(),
            'COUNTRY'    => $this->getCountry(),
            'MERCH_GMT'  => $this->getMerchGmt(),
            'LANG'       => $this->getLang(),
            'NAME'       => $this->getCustomerName(),
            'M_INFO'     => $this->getMInfo(),
        ];

        foreach ($optionalFields as $key => $value) {
            if ($value !== null) {
                $data[$key] = $value;
            }
        }
    }

    /**
     * Validate authorization-specific required fields.
     *
     * @return void
     * @throws \InvalidArgumentException When validation fails
     */
    protected function validateAuthorizationSpecificFields()
    {
        // Validate field lengths
        $this->validateFieldLengths();

        // Validate ORDER field is numeric
        $this->validateOrderField();

        // Validate amount is positive
        if ($this->getAmount() <= 0) {
            throw new \InvalidArgumentException('Amount must be greater than zero');
        }

        // Validate currency format
        $currency = $this->getCurrency();
        if ($currency && strlen($currency) !== Constants::MAX_LENGTH_CURRENCY) {
            throw new \InvalidArgumentException('Currency must be exactly 3 characters');
        }
    }

    /**
     * Send the data and create response.
     *
     * @param array $data The request data
     * @return AuthorizeResponse
     */
    public function sendData($data)
    {
        return $this->response = new AuthorizeResponse($this, $data);
    }
}
