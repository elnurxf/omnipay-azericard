<?php

namespace Omnipay\AzeriCard\Message\Responses;

use Omnipay\Common\Message\AbstractResponse;

/**
 * AzeriCard checkout response handler.
 */
class CheckoutResponse extends AbstractResponse
{
    /**
     * There is no "success" at this stage.
     *
     * @return bool
     */
    public function isSuccessful()
    {

        return true;

        if (is_string($this->data) && trim($this->data) === '0') {
            return true;
        }
    }

}
