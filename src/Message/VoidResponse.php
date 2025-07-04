<?php

namespace Omnipay\AzeriCard\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * AzeriCard void response handler.
 */
class VoidResponse extends AbstractResponse
{
    /**
     * Is the void successful?
     *
     * @return bool
     */
    public function isSuccessful()
    {
        return (isset($this->data['ACTION']) && (string) $this->data['ACTION'] === '0');
    }

    /**
     * Get the response message.
     *
     * @return string|null
     */
    public function getMessage()
    {
        return $this->data['DESC'] ?? null;
    }
}
