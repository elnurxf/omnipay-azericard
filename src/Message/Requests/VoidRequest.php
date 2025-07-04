<?php

namespace Omnipay\AzeriCard\Message\Requests;

use Omnipay\AzeriCard\Message\Responses\VoidResponse;

use Omnipay\AzeriCard\Constants;

/**
 * AzeriCard void (offline reversal) request (TRTYPE=24).
 */
class VoidRequest extends AbstractRequest
{
    /**
     * Get the void request data.
     *
     * @return array
     */
    public function getData()
    {
        $this->validateRequiredFields([
            'amount',
            'terminalId',
            'merchUrl',
        ]);

        $data = [
            'AMOUNT'    => $this->formatAmount($this->getAmount()),
            'CURRENCY'  => $this->getCurrency() ?: Constants::CURRENCY_AZN,
            'TERMINAL'  => $this->getTerminalId(),
            'TRTYPE'    => Constants::TRTYPE_VOID,
            'TIMESTAMP' => $this->getTimestamp() ?: $this->generateTimestamp(),
            'NONCE'     => $this->getNonce() ?: $this->generateNonce(),
            'MERCH_URL' => $this->getMerchUrl(),
        ];

        $data['P_SIGN'] = $this->sign([
            $data['AMOUNT'],
            $data['CURRENCY'],
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
     * @return VoidResponse
     */
    public function sendData($data)
    {
        return $this->response = new VoidResponse($this, $data);
    }
}
