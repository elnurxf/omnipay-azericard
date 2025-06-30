<?php

namespace Omnipay\AzeriCard\Message;

class CompleteSaleRequest extends AbstractRequest
{
    public function getData()
    {
        $timestamp = $this->generateTimestamp();
        $nonce = $this->generateNonce();
        $amount = number_format($this->getAmount(), 2, '.', '');

        $data = [
            'AMOUNT'    => $amount,
            'CURRENCY'  => 'AZN',
            'ORDER'     => $this->getTransactionId(),
            'RRN'       => $this->getParameter('rrn'),
            'INT_REF'   => $this->getParameter('intRef'),
            'TERMINAL'  => $this->getParameter('terminalId'),
            'TRTYPE'    => '21',
            'TIMESTAMP' => $timestamp,
            'NONCE'     => $nonce,
        ];

        $data['P_SIGN'] = $this->sign([
            $amount,
            $data['CURRENCY'],
            $data['TERMINAL'],
            $data['TRTYPE'],
            $data['ORDER'],
            $data['RRN'],
            $data['INT_REF'],
        ]);

        return $data;
    }

    public function sendData($data)
    {
        return $this->response = new PurchaseResponse($this, $data);
    }
}
