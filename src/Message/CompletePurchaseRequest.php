<?php

namespace Omnipay\AzeriCard\Message;

/**
 * AzeriCard completion request (callback after customer returns from bank).
 */
class CompletePurchaseRequest extends AbstractRequest
{
    /**
     * Get the completion request data (callback from gateway).
     *
     * @return array
     */
    public function getData()
    {
        // In a real-world scenario, you might want to validate the
        // incoming POST data, verify P_SIGN, etc.
        // For Omnipay, just return $_POST (or whatever data Omnipay provides).
        return $this->httpRequest->request->all();
    }

    /**
     * Send the data and create response.
     *
     * @param array $data
     * @return CompletePurchaseResponse
     */
    public function sendData($data)
    {
        return $this->response = new CompletePurchaseResponse($this, $data);
    }
}
