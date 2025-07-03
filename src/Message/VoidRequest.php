<?php

namespace Omnipay\AzeriCard\Message;

use Omnipay\AzeriCard\Constants;

class VoidRequest extends AbstractRequest
{
    /**
     * Get the void request data.
     *
     * @return array The request data
     * @throws \InvalidArgumentException When validation fails
     */
    public function getData()
    {
        $this->validate('transactionId', 'terminalId');
        $this->validateVoidSpecificFields();

        $timestamp = $this->generateTimestamp();
        $nonce     = $this->generateNonce();

        $data = [
            'ORDER'         => $this->getOrder(),
            'RRN'           => $this->getRRN(),
            'INT_REF'       => $this->getIntRef(),
            'TERMINAL'      => $this->getTerminalId(),
            'TRTYPE'        => Constants::TRTYPE_VOID,
            'TIMESTAMP'     => $timestamp,
            'NONCE'         => $nonce,
            'MAC_KEY_INDEX' => 0,
        ];

        $data['P_SIGN'] = $this->sign([
            $data['TERMINAL'],
            $data['TRTYPE'],
            $data['ORDER'],
            $data['RRN'],
            $data['INT_REF'],
        ]);

        return $data;
    }

    /**
     * Send the data and create response.
     *
     * @param array $data The request data
     * @return VoidResponse
     */
    public function sendData($data)
    {
        return $this->response = new VoidResponse($this, $data);
    }

    /**
     * Validate void specific required fields.
     *
     * @return void
     * @throws \InvalidArgumentException When required fields are missing
     */
    protected function validateVoidSpecificFields()
    {
        if (empty($this->getRRN())) {
            throw new \InvalidArgumentException('RRN is required for void transactions');
        }

        if (empty($this->getIntRef())) {
            throw new \InvalidArgumentException('INT_REF is required for void transactions');
        }
    }

}
