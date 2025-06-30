<?php

namespace Omnipay\AzeriCard\Message;

class PurchaseRequest extends AbstractRequest
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
            'DESC'      => $this->getDescription(),
            'MERCH_URL' => $this->getReturnUrl(),
            'TERMINAL'  => $this->getParameter('terminalId'),
            'EMAIL'     => $this->getParameter('email'),
            'TRTYPE'    => '1',
            'COUNTRY'   => 'AZ',
            'MERCH_GMT' => '+4',
            'TIMESTAMP' => $timestamp,
            'NONCE'     => $nonce,
            'LANG'      => 'EN',
            'BACKREF'   => $this->getReturnUrl(),
            'NAME'      => $this->getParameter('name'),
        ];

        $data['P_SIGN'] = $this->sign([
            $amount,
            $data['CURRENCY'],
            $data['TERMINAL'],
            $data['TRTYPE'],
            $timestamp,
            $nonce,
            $data['MERCH_URL'],
        ]);

        return $data;
    }

    public function sendData($data)
    {
        return $this->response = new PurchaseResponse($this, $data);
    }
}
