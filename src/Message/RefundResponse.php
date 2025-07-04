<?php

namespace Omnipay\AzeriCard\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * AzeriCard refund response handler.
 */
class RefundResponse extends AbstractResponse implements RedirectResponseInterface
{
    /**
     * Is a redirect required?
     *
     * @return bool
     */
    public function isRedirect()
    {
        // Usually not a redirect, unless you want to show a form for confirmation
        return false;
    }

    /**
     * Get the redirect URL (not used).
     *
     * @return string|null
     */
    public function getRedirectUrl()
    {
        return null;
    }

    /**
     * Is the refund successful?
     *
     * @return bool
     */
    public function isSuccessful()
    {
        // AzeriCard returns ACTION=0 for success
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
