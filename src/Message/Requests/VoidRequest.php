<?php

namespace Omnipay\AzeriCard\Message\Requests;

use Omnipay\AzeriCard\Constants;
use Omnipay\AzeriCard\Message\Responses\VoidResponse;

/**
 * AzeriCard void (offline reversal) request (TRTYPE=24).
 */
class VoidRequest extends AbstractRequest
{
    /**
     * Get the void request data.
     *
     * @return array
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
            'AMOUNT'    => $this->formatAmount($this->getAmount()),
            'CURRENCY'  => $this->getCurrency() ?: Constants::CURRENCY_AZN,
            'ORDER'     => $this->getOrder(),
            'RRN'       => $this->getRRN(),
            'INT_REF'   => $this->getIntRef(),
            'TERMINAL'  => $this->getTerminalId(),
            'TRTYPE'    => Constants::TRTYPE_VOID,
            'TIMESTAMP' => $this->getTimestamp() ?: $this->generateTimestamp(),
            'NONCE'     => $this->getNonce() ?: $this->generateNonce(),
        ];

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
     * @return VoidResponse
     */
    public function sendData($data)
    {
        return $this->response = new VoidResponse($this, $data);
    }
}
