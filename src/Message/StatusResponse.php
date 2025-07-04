<?php

namespace Omnipay\AzeriCard\Message;

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
        return (isset($this->data['ACTION']) && (string) $this->data['ACTION'] === '0');
    }

    /**
     * Get the transaction status message.
     *
     * @return string|null
     */
    public function getMessage()
    {
        return $this->data['DESC'] ?? null;
    }

    /**
     * Get the status code.
     *
     * @return string|null
     */
    public function getCode()
    {
        return $this->data['ACTION'] ?? null;
    }
}
