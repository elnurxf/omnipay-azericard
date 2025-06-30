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
        return $this->data['DESC'] ?? null;
    }
}
