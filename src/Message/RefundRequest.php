<?php

namespace Omnipay\AzeriCard\Message;

use Omnipay\AzeriCard\Constants;

class RefundRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('amount', 'transactionId', 'terminalId');
        $this->validateRefundSpecificFields();

        $timestamp = $this->generateTimestamp();
        $nonce     = $this->generateNonce();
        $amount    = $this->formatAmount($this->getAmount());

        $data = [
            'AMOUNT'        => $amount,
            'CURRENCY'      => Constants::CURRENCY_AZN,
            'ORDER'         => $this->getTransactionId(),
            'RRN'           => $this->getRRN(),
            'INT_REF'       => $this->getIntRef(),
            'TERMINAL'      => $this->getTerminalId(),
            'TRTYPE'        => Constants::TRTYPE_REFUND,
            'TIMESTAMP'     => $timestamp,
            'NONCE'         => $nonce,
            'MAC_KEY_INDEX' => 0,
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
        return $this->response = new RefundResponse($this, $data);
    }

    protected function validateRefundSpecificFields()
    {
        if (empty($this->getRRN())) {
            throw new \InvalidArgumentException('RRN is required for refunds');
        }

        if (empty($this->getIntRef())) {
            throw new \InvalidArgumentException('INT_REF is required for refunds');
        }
    }

    public function getRRN()
    {
        return $this->getParameter('rrn');
    }

    public function setRRN($value)
    {
        return $this->setParameter('rrn', $value);
    }

    public function getIntRef()
    {
        return $this->getParameter('intRef');
    }

    public function setIntRef($value)
    {
        return $this->setParameter('intRef', $value);
    }
}
