<?php

namespace Omnipay\AzeriCard\Message;

use Omnipay\Common\Message\AbstractResponse;

class CompletePurchaseResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        return isset($this->data['ACTION']) && $this->data['ACTION'] === '0';
    }

    public function getTransactionReference()
    {
        return $this->data['INT_REF'] ?? null;
    }

    public function getMessage()
    {
        if (! $this->isSuccessful()) {
            return $this->data['DESC'] ?? 'Transaction failed';
        }
        return $this->data['DESC'] ?? 'Transaction successful';
    }

    public function getCode()
    {
        return $this->data['ACTION'] ?? null;
    }

    public function getRRN()
    {
        return $this->data['RRN'] ?? null;
    }
}
