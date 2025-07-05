<?php

namespace Omnipay\AzeriCard\Message\Responses;

use Omnipay\Common\Message\AbstractResponse;

/**
 * AzeriCard status inquiry response handler.
 */
class StatusResponse extends AbstractResponse
{
    /**
     * Is the status inquiry successful?
     *
     * @return bool
     */
    public function isSuccessful()
    {
        return true;
        //return (isset($this->data['ACTION']) && (string) $this->data['ACTION'] === '0');;
    }

    /**
     * Get the transaction status message.
     *
     * @return string|null
     */
    public function getResult()
    {
        return $this->data;
    }

}
