
<?php

namespace Omnipay\AzeriCard\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\AzeriCard\Constants;

class RefundResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        return isset($this->data['ACTION']) && $this->data['ACTION'] === Constants::ACTION_SUCCESS;
    }

    public function getTransactionReference()
    {
        return $this->data['INT_REF'] ?? null;
    }

    public function getMessage()
    {
        if (!$this->isSuccessful()) {
            return $this->data['DESC'] ?? 'Refund failed';
        }
        return $this->data['DESC'] ?? 'Refund successful';
    }

    public function getCode()
    {
        return $this->data['ACTION'] ?? null;
    }

    public function getRRN()
    {
        return $this->data['RRN'] ?? null;
    }
    
    public function getTransactionId()
    {
        return $this->data['ORDER'] ?? null;
    }
}
