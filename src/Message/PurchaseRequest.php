<?php

namespace Omnipay\AzeriCard\Message;

use Omnipay\AzeriCard\Constants;

class PurchaseRequest extends AbstractRequest
{
    /**
     * Get the purchase request data.
     *
     * @return array The request data
     * @throws \InvalidArgumentException When validation fails
     */
    public function getData()
    {
        $this->validate('amount', 'terminalId');
        $this->validatePurchaseSpecificFields();

        $timestamp = $this->getTimestamp() ?: $this->generateTimestamp();
        $nonce     = $this->getNonce() ?: $this->generateNonce();
        $amount    = $this->formatAmount($this->getAmount());
        $order     = $this->getOrder() ?: $this->getTransactionId();

        $data = [
            'AMOUNT'        => $amount,
            'CURRENCY'      => $this->getCurrency() ?: Constants::CURRENCY_AZN,
            'ORDER'         => $order,
            'DESC'          => $this->getDescription() ?: 'Payment',
            'TERMINAL'      => $this->getTerminalId(),
            'TRTYPE'        => $this->getTrtype() ?: Constants::TRTYPE_PURCHASE,
            'BACKREF'       => $this->getReturnUrl(),
            'TIMESTAMP'     => $timestamp,
            'NONCE'         => $nonce,
            'MAC_KEY_INDEX' => 0,
        ];

        // Add optional fields if provided
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
            $data['BACKREF'],
        ]);

        return $data;
    }

    /**
     * Validate purchase-specific required fields.
     *
     * @return void
     * @throws \InvalidArgumentException When validation fails
     */
    protected function validatePurchaseSpecificFields()
    {
        if (empty($this->getReturnUrl())) {
            throw new \InvalidArgumentException('Return URL (BACKREF) is required for purchases');
        }

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

        // Validate transaction type
        $trtype = $this->getTrtype();
        if ($trtype !== null && ! in_array($trtype, [Constants::TRTYPE_PRE_AUTH, Constants::TRTYPE_AUTH])) {
            throw new \InvalidArgumentException('Invalid transaction type');
        }
    }

    /**
     * Send the data and create response.
     *
     * @param array $data The request data
     * @return PurchaseResponse
     */
    public function sendData($data)
    {
        return $this->response = new PurchaseResponse($this, $data);
    }

    public function getCustomerName()
    {
        return $this->getParameter('name');
    }

    public function setCustomerName($value)
    {
        return $this->setParameter('name', $value);
    }
}
