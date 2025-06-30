
<?php

namespace Omnipay\AzeriCard\Message;

use Omnipay\AzeriCard\Constants;

class PurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('amount', 'transactionId', 'returnUrl', 'terminalId');
        
        $timestamp = $this->generateTimestamp();
        $nonce = $this->generateNonce();
        $amount = $this->formatAmount($this->getAmount());

        $data = [
            'AMOUNT'    => $amount,
            'CURRENCY'  => Constants::CURRENCY_AZN,
            'ORDER'     => $this->getTransactionId(),
            'DESC'      => $this->getDescription() ?: $this->getTransactionId(),
            'MERCH_URL' => $this->getReturnUrl(),
            'TERMINAL'  => $this->getTerminalId(),
            'EMAIL'     => $this->getParameter('email'),
            'TRTYPE'    => Constants::TRTYPE_PURCHASE,
            'COUNTRY'   => Constants::COUNTRY_AZ,
            'MERCH_GMT' => Constants::TIMEZONE_AZ,
            'TIMESTAMP' => $timestamp,
            'NONCE'     => $nonce,
            'LANG'      => $this->getParameter('lang') ?: Constants::LANG_EN,
            'BACKREF'   => $this->getReturnUrl(),
            'NAME'      => $this->getParameter('name'),
            'MAC_KEY_INDEX' => 0,
        ];

        // Remove null values
        $data = array_filter($data, function($value) {
            return $value !== null && $value !== '';
        });

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
