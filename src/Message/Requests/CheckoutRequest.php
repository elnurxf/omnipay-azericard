<?php

namespace Omnipay\AzeriCard\Message\Requests;

use Omnipay\AzeriCard\Constants;
use Omnipay\AzeriCard\Message\Responses\CheckoutResponse;

/**
 * AzeriCard 3D-Secure checkout request.
 */
class CheckoutRequest extends AbstractRequest
{
    /**
     * Get the checkout request data.
     *
     * @return array
     * @throws \InvalidArgumentException
     */
    public function getData()
    {
        $this->validateRequiredFields([
            'amount',
            'order',
            'rrn',
            'int_ref',
        ]);

        $data = [
            'ORDER'     => $this->getOrder(),
            'AMOUNT'    => $this->formatAmount($this->getAmount()),
            'CURRENCY'  => $this->getCurrency() ?: Constants::CURRENCY_AZN,
            'TERMINAL'  => $this->getTerminalId(),
            'RRN'       => $this->getRRN(),
            'INT_REF'   => $this->getIntRef(),
            'TRTYPE'    => Constants::TRTYPE_COMPLETE_AUTH,
            'TIMESTAMP' => $this->getTimestamp() ?: $this->generateTimestamp(),
            'NONCE'     => $this->getNonce() ?: $this->generateNonce(),
        ];

        // Signature
        $data['P_SIGN'] = $this->sign([
            $data['AMOUNT'],
            $data['CURRENCY'],
            $data['TERMINAL'],
            $data['TRTYPE'],
            $data['ORDER'],
            $data['RRN'],
            $data['INT_REF'],
        ]);

        return $data;
    }

    /**
     * Send the data and create response.
     *
     * @param array $data
     * @return CheckoutResponse
     */
    public function sendData($data)
    {
        return $this->response = new CheckoutResponse($this, $data);
    }
}
