<?php

namespace Omnipay\AzeriCard\Message;

class StatusRequest extends AbstractRequest
{
    public function getData()
    {
        $timestamp = $this->generateTimestamp();
        $nonce     = $this->generateNonce();

        $data = [
            'TRAN_TRTYPE' => $this->getParameter('originalTrtype'),
            'ORDER'       => $this->getTransactionId(),
            'TERMINAL'    => $this->getParameter('terminalId'),
            'TRTYPE'      => '90',
            'TIMESTAMP'   => $timestamp,
            'NONCE'       => $nonce,
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
