<?php

namespace Omnipay\AzeriCard\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

class AuthorizeResponse extends AbstractResponse implements RedirectResponseInterface
{
    /**
     * Check if the request was successful.
     * For authorization requests, we need to redirect to the gateway.
     *
     * @return bool Always false for redirect responses
     */
    public function isSuccessful()
    {
        return false;
    }

    /**
     * Check if this is a redirect response.
     *
     * @return bool Always true for authorization requests
     */
    public function isRedirect()
    {
        return true;
    }

    /**
     * Get the redirect URL.
     *
     * @return string The gateway URL
     */
    public function getRedirectUrl()
    {
        return $this->getRequest()->getEndpoint();
    }

    /**
     * Get the redirect method (POST for form submission).
     *
     * @return string Always 'POST'
     */
    public function getRedirectMethod()
    {
        return 'POST';
    }

    /**
     * Get the redirect data (form fields).
     *
     * @return array The form data to submit
     */
    public function getRedirectData()
    {
        return $this->data;
    }

    /**
     * Get the transaction reference.
     *
     * @return string|null The transaction reference
     */
    public function getTransactionReference()
    {
        return $this->data['ORDER'] ?? null;
    }

    /**
     * Get a message describing the response.
     *
     * @return string The response message
     */
    public function getMessage()
    {
        return 'Redirecting to AzeriCard gateway for authorization';
    }
}
