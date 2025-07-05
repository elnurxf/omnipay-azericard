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
            'ORDER'     => $this->getOrder(),
            'TERMINAL'  => $this->getTerminalId(),
            'TRTYPE'    => Constants::TRTYPE_STATUS, // 90
            'TIMESTAMP' => $this->getTimestamp() ?: $this->generateTimestamp(),
            'NONCE'     => $this->getNonce() ?: $this->generateNonce(),
        ];

        // Some banks require MERCH_URL even for status check, so add if present:
        if ($this->getMerchUrl()) {
            $data['MERCH_URL'] = $this->getMerchUrl();
        }

        // If your bank requires TRAN_TRTYPE, add it! (But only if you need to check a specific trtype.)
        // if (defined('YOUR_TRAN_TRTYPE_CONSTANT')) $data['TRAN_TRTYPE'] = Constants::TRTYPE_PRE_AUTH;

        // Per docs, signature field order for TRTYPE=90 is usually:
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
                'Accept' => 'application/json'
            ],
            http_build_query($data)
        );

        $body = (string) $httpResponse->getBody();
        $responseData = [];
        parse_str($body, $responseData);

        return $this->response = new StatusResponse($this, $responseData);
    }
}
