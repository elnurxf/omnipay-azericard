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

        $timestamp = $this->getTimestamp() ?: $this->generateTimestamp();
        $nonce     = $this->getNonce() ?: $this->generateNonce();
        $amount    = $this->formatAmount($this->getAmount());
        $order     = $this->getOrder() ?: $this->getTransactionId();

        $data = [
            'AMOUNT'        => $amount,
            'CURRENCY'      => $this->getCurrency() ?: Constants::CURRENCY_AZN,
            'ORDER'         => $order,
            'DESC'          => $this->getDescription() ?: 'Authorization',
            'TERMINAL'      => $this->getTerminalId(),
            'TRTYPE'        => Constants::TRTYPE_PRE_AUTH,
            'TIMESTAMP'     => $timestamp,
            'NONCE'         => $nonce,
            'MAC_KEY_INDEX' => 0,
        ];

        // Add optional fields if provided
        if ($this->getReturnUrl()) {
            $data['BACKREF'] = $this->getReturnUrl();
        }

        if ($this->getMerchName()) {
            $data['MERCH_NAME'] = $this->getMerchName();
        }

        if ($this->getMerchUrl()) {
            $data['MERCH_URL'] = $this->getMerchUrl();
        }

        if ($this->getEmail()) {
            $data['EMAIL'] = $this->getEmail();
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

        if ($this->getCustomerName()) {
            $data['NAME'] = $this->getCustomerName();
        }

        if ($this->getMInfo()) {
            $data['M_INFO'] = $this->getMInfo();
        }

        // Generate signature with core fields
        $data['P_SIGN'] = $this->sign([
            $amount,
            $data['CURRENCY'],
            $order,
            $data['DESC'],
            $data['TERMINAL'],
            $data['TRTYPE'],
        ]);

        return $data;
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

    /**
     * Get additional getter/setter methods for authorization-specific fields
     */

    public function getMerchName()
    {
        return $this->getParameter('merchName');
    }

    public function setMerchName($value)
    {
        return $this->setParameter('merchName', $value);
    }

    public function getMerchUrl()
    {
        return $this->getParameter('merchUrl');
    }

    public function setMerchUrl($value)
    {
        return $this->setParameter('merchUrl', $value);
    }

    public function getEmail()
    {
        return $this->getParameter('email');
    }

    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
    }

    public function getCountry()
    {
        return $this->getParameter('country');
    }

    public function setCountry($value)
    {
        return $this->setParameter('country', $value);
    }

    public function getMerchGmt()
    {
        return $this->getParameter('merchGmt');
    }

    public function setMerchGmt($value)
    {
        return $this->setParameter('merchGmt', $value);
    }

    public function getLang()
    {
        return $this->getParameter('lang');
    }

    public function setLang($value)
    {
        return $this->setParameter('lang', $value);
    }

    public function getCustomerName()
    {
        return $this->getParameter('name');
    }

    public function setCustomerName($value)
    {
        return $this->setParameter('name', $value);
    }

    public function getTrtype()
    {
        return $this->getParameter('trtype');
    }

    public function setTrtype($value)
    {
        return $this->setParameter('trtype', $value);
    }
}
