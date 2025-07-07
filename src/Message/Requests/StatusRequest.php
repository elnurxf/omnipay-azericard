<?php

namespace Omnipay\AzeriCard\Message\Requests;

use Omnipay\AzeriCard\Constants;
use Omnipay\AzeriCard\Message\Responses\StatusResponse;

/**
 * AzeriCard status inquiry request (TRTYPE=90).
 */
class StatusRequest extends AbstractRequest
{
    /**
     * Get the status request data.
     *
     * @return array
     */
    public function getData()
    {
        $this->validateRequiredFields([
            'terminalId',
            'order',
        ]);

        $data = [
            'TRAN_TRTYPE' => $this->getTranTrtype(),
            'ORDER'       => $this->getOrder(),
            'TERMINAL'    => $this->getTerminalId(),
            'TRTYPE'      => Constants::TRTYPE_STATUS,
            'TIMESTAMP'   => $this->getTimestamp() ?: $this->generateTimestamp(),
            'NONCE'       => $this->getNonce() ?: $this->generateNonce(),
        ];

        $data['P_SIGN'] = $this->sign([
            $data['ORDER'],
            $data['TERMINAL'],
            $data['TRTYPE'],
            $data['TIMESTAMP'],
            $data['NONCE'],
        ]);

        return $data;
    }

    /**
     * Send the status inquiry to AzeriCard and parse the reply.
     *
     * @param array $data
     * @return StatusResponse
     */
    public function sendData($data)
    {
        $httpResponse = $this->httpClient->request(
            'POST',
            $this->getEndpoint(),
            [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Accept'       => 'application/json',
            ],
            http_build_query($data)
        );

        $body         = (string) $httpResponse->getBody();
        $responseData = [];
        parse_str($body, $responseData);

        return $this->response = new StatusResponse($this, $responseData);
    }

    /**
     * Send the data and create response.
     *
     * @param array $data
     * @return StatusResponse
     */
    /*public function sendData($data)
    {
        return $this->response = new StatusResponse($this, $data);
    }*/
}
