<?php

namespace Omnipay\AzeriCard\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * AzeriCard authorize response handler.
 */
class AuthorizeResponse extends AbstractResponse implements RedirectResponseInterface
{
    /**
     * Is a redirect required?
     *
     * @return bool
     */
    public function isRedirect()
    {
        return true;
    }

    /**
     * Get the redirect URL for the customer.
     *
     * @return string|null
     */
    public function getRedirectUrl()
    {
        return $this->getRequest()->getEndpoint();
    }

    /**
     * Get the redirect method (POST).
     *
     * @return string
     */
    public function getRedirectMethod()
    {
        return 'POST';
    }

    /**
     * Get redirect data (fields to post).
     *
     * @return array
     */
    public function getRedirectData()
    {
        return $this->data;
    }

    /**
     * There is no "success" at this stage.
     *
     * @return bool
     */
    public function isSuccessful()
    {
        return false;
    }

    /**
     * Error message (not applicable here).
     *
     * @return string|null
     */
    public function getMessage()
    {
        return null;
    }
}
