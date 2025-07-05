<?php

namespace Omnipay\AzeriCard\Message\Requests;

use Omnipay\AzeriCard\Constants;
use Omnipay\AzeriCard\Message\Responses\AuthorizeResponse;

/**
 * AzeriCard 3D-Secure pre-authorization request.
 */
class AuthorizeRequest extends AbstractRequest
{
    /**
     * Get the authorization request data.
     *
     * @return array
     * @throws \InvalidArgumentException
     */
    public function getData()
    {
        $this->validateRequiredFields([
            'amount',
            'terminalId',
            'merchUrl',
        ]);

        $data = [
            'AMOUNT'    => $this->formatAmount($this->getAmount()),
            'CURRENCY'  => $this->getCurrency() ?: Constants::CURRENCY_AZN,
            'TERMINAL'  => $this->getTerminalId(),
            'TRTYPE'    => Constants::TRTYPE_PRE_AUTH,
            'TIMESTAMP' => $this->getTimestamp() ?: $this->generateTimestamp(),
            'NONCE'     => $this->getNonce() ?: $this->generateNonce(),
            'MERCH_URL' => $this->getMerchUrl(),
        ];

        // Optional/documented fields
        if ($this->getOrder()) {
            $data['ORDER'] = $this->getOrder();
        }

        if ($this->getDescription()) {
            $data['DESC'] = $this->getDescription();
        }

        if ($this->getEmail()) {
            $data['EMAIL'] = $this->getEmail();
        }

        if ($this->getCustomerName()) {
            $data['NAME'] = $this->getCustomerName();
        }

        if ($this->getMerchName()) {
            $data['MERCH_NAME'] = $this->getMerchName();
        }

        if ($this->getCountry()) {
            $data['COUNTRY'] = $this->getCountry();
        }

        if ($this->getMerchGmt()) {
            $data['MERCH_GMT'] = $this->getMerchGmt();
        }

        if ($this->getLang()) {
            $data['LANG'] = $this->getLang();
        }

        if ($this->getMInfo()) {
            $data['M_INFO'] = $this->getMInfo();
        }

        if ($this->getReturnUrl()) {
            $data['BACKREF'] = $this->getReturnUrl();
        }

        // Signature
        $data['P_SIGN'] = $this->sign([
            $data['AMOUNT'],
            $data['CURRENCY'],
            $data['TERMINAL'],
            $data['TRTYPE'],
            $data['TIMESTAMP'],
            $data['NONCE'],
            $data['MERCH_URL'],
        ]);

        return $data;
    }

    /**
     * Send the data and create response.
     *
     * @param array $data
     * @return AuthorizeResponse
     */
    public function sendData($data)
    {
        return $this->response = new AuthorizeResponse($this, $data);
    }
}
