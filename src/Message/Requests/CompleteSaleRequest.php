<?php

namespace Omnipay\AzeriCard\Message\Requests;

use Omnipay\AzeriCard\Message\Responses\CompleteSaleResponse;

/**
 * Handle AzeriCard callback for sale completion.
 */
class CompleteSaleRequest extends AbstractRequest
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
     * @return CompleteSaleResponse
     */
    public function sendData($data)
    {
        return $this->response = new CompleteSaleResponse($this, $data);
    }
}
