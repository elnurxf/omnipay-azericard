<?php

namespace Omnipay\AzeriCard\Message\Requests;

use Omnipay\AzeriCard\Message\Responses\PurchaseResponse;

class TokenPaymentRequest extends AbstractRequest
{
    public function getData()
    {
        $timestamp = $this->generateTimestamp();
        $nonce     = $this->generateNonce();
        $amount    = number_format($this->getAmount(), 2, '.', '');

        $data = [
            'AMOUNT'    => $amount,
            'CURRENCY'  => 'AZN',
            'ORDER'     => $this->getTransactionId(),
            'TOKEN'     => $this->getParameter('token'),
            'TERMINAL'  => $this->getParameter('terminalId'),
            'TRTYPE'    => '1',
            'TIMESTAMP' => $timestamp,
            'NONCE'     => $nonce,
        ];

        $data['P_SIGN'] = $this->sign([
            $amount,
            $data['CURRENCY'],
            $data['TERMINAL'],
            $data['TRTYPE'],
            $timestamp,
            $nonce,
        ]);

        return $data;
    }

    public function sendData($data)
    {
        return $this->response = new PurchaseResponse($this, $data);
    }
}
