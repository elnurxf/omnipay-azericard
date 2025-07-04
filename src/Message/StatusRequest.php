<?php

namespace Omnipay\AzeriCard\Message;

use Omnipay\AzeriCard\Constants;

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
            'merchUrl',
        ]);

        $data = [
            'ORDER'     => $this->getOrder(),
            'TERMINAL'  => $this->getTerminalId(),
            'TRTYPE'    => Constants::TRTYPE_STATUS,
            'TIMESTAMP' => $this->getTimestamp() ?: $this->generateTimestamp(),
            'NONCE'     => $this->getNonce() ?: $this->generateNonce(),
            'MERCH_URL' => $this->getMerchUrl(),
        ];

        $data['P_SIGN'] = $this->sign([
            $data['ORDER'],
            $data['TERMINAL'],
            $data['TRTYPE'],
            $data['TIMESTAMP'],
            $data['NONCE'],
            $data['MERCH_URL'],
        ]);

        return $data;
    }

    /**
     * Send the data and create response.
     *
     * @param array $data
     * @return StatusResponse
     */
    public function sendData($data)
    {
        return $this->response = new StatusResponse($this, $data);
    }
}
