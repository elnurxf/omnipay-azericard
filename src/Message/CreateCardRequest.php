<?php

namespace Omnipay\AzeriCard\Message;

class CreateCardRequest extends AbstractRequest
{
    public function getData()
    {
        $timestamp = $this->generateTimestamp();
        $nonce     = $this->generateNonce();

        $data = [
            'ORDER'        => $this->getTransactionId(),
            'TERMINAL'     => $this->getParameter('terminalId'),
            'TRTYPE'       => '1',
            'TOKEN_ACTION' => 'REGISTER',
            'CURRENCY'     => 'AZN',
            'DESC'         => 'Token registration',
            'MERCH_URL'    => $this->getReturnUrl(),
            'EMAIL'        => $this->getParameter('email'),
            'NAME'         => $this->getParameter('name'),
            'TIMESTAMP'    => $timestamp,
            'NONCE'        => $nonce,
        ];

        $data['P_SIGN'] = $this->sign([
            $data['ORDER'],
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
