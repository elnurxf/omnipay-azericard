<?php

namespace Omnipay\AzeriCard\Message;

class AuthorizeRequest extends AbstractRequest
{
    public function getData()
    {
        $timestamp = $this->generateTimestamp();
        $nonce = $this->generateNonce();
        $amount = number_format($this->getAmount(), 2, '.', '');

        $data = [
            'AMOUNT'     => $amount,
            'CURRENCY'   => 'AZN',
            'ORDER'      => $this->getTransactionId(),
            'DESC'       => $this->getDescription(),
            'TERMINAL'   => $this->getParameter('terminalId'),
            'EMAIL'      => $this->getParameter('email'),
            'NAME'       => $this->getParameter('name'),
            'TRTYPE'     => '0', // Authorization only
            'COUNTRY'    => 'AZ',
            'MERCH_GMT'  => '+4',
            'TIMESTAMP'  => $timestamp,
            'NONCE'      => $nonce,
            'MERCH_URL'  => $this->getReturnUrl(),
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
