<?php

namespace Omnipay\AzeriCard\Message;

/**
 * Handle AzeriCard callback for purchase.
 */
class CompletePurchaseRequest extends AbstractRequest
{
    /**
     * Get data sent from AzeriCard in callback.
     *
     * @return array
     */
    public function getData()
    {
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
