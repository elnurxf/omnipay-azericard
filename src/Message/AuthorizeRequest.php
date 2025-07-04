<?php

namespace Omnipay\AzeriCard\Message;

use Omnipay\AzeriCard\Constants;

/**
 * AzeriCard 3D-Secure pre-authorization request.
 */
class AuthorizeRequest extends AbstractRequest
{
    /**
     * Build the authorization request data for AzeriCard.
     *
     * @return array The request data
     * @throws \InvalidArgumentException When validation fails
     */
    public function getData()
    {
        $this->validate('amount', 'terminalId');
        $this->validateAuthorizationSpecificFields();

        $timestamp = $this->getTimestamp() ?: $this->generateTimestamp();
        $nonce     = $this->getNonce() ?: $this->generateNonce();
        $amount    = $this->formatAmount($this->getAmount());
        $order     = $this->getOrder();

        // Compose full data array, fill empty strings for optional fields
        $data = [
            'AMOUNT'        => $amount,
            'CURRENCY'      => $this->getCurrency() ?: Constants::CURRENCY_AZN,
            'ORDER'         => $order,
            'DESC'          => $this->getDescription() ?: 'Authorization',
            'TERMINAL'      => $this->getTerminalId(),
            'TRTYPE'        => Constants::TRTYPE_PRE_AUTH,
            'TIMESTAMP'     => $timestamp,
            'NONCE'         => $nonce,
            'MAC_KEY_INDEX' => Constants::MAC_KEY_INDEX,
            'BACKREF'       => $this->getReturnUrl() ?: '',
            'MERCH_NAME'    => $this->getMerchName() ?: '',
            'MERCH_URL'     => $this->getMerchUrl() ?: '',
            'EMAIL'         => $this->getEmail() ?: '',
            'COUNTRY'       => $this->getCountry() ?: '',
            'MERCH_GMT'     => $this->getMerchGmt() ?: '',
            'LANG'          => $this->getLang() ?: '',
            'NAME'          => $this->getCustomerName() ?: '',
            'M_INFO'        => $this->getMInfo() ?: '',
        ];

        // Signature fields - strict order, all included (empty string if not set)
        $signFields = [
            $data['AMOUNT'],
            $data['CURRENCY'],
            $data['ORDER'],
            $data['DESC'],
            $data['TERMINAL'],
            $data['TRTYPE'],
        ];

        $data['P_SIGN'] = $this->sign($signFields);

        return $data;
    }

    /**
     * Validate authorization-specific constraints.
     *
     * @throws \InvalidArgumentException
     * @return void
     */
    protected function validateAuthorizationSpecificFields()
    {
        $this->validateFieldLengths();
        $this->validateOrderField();

        if ($this->getAmount() <= 0) {
            throw new \InvalidArgumentException('Amount must be greater than zero');
        }
        $currency = $this->getCurrency();
        if ($currency && strlen($currency) !== 3) {
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
