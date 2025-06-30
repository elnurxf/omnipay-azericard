<?php

namespace Omnipay\AzeriCard\Message;

use Omnipay\AzeriCard\Constants;

class StatusRequest extends AbstractRequest
{
    /**
     * Get the status check request data.
     *
     * @return array The request data
     * @throws \InvalidArgumentException When validation fails
     */
    public function getData()
    {
        $this->validate('transactionId', 'terminalId');
        $this->validateStatusSpecificFields();

        $timestamp = $this->generateTimestamp();
        $nonce     = $this->generateNonce();

        $data = [
            'ORDER'         => $this->getTransactionId(),
            'RRN'           => $this->getRRN(),
            'INT_REF'       => $this->getIntRef(),
            'TERMINAL'      => $this->getTerminalId(),
            'TRTYPE'        => Constants::TRTYPE_STATUS,
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
     * @return StatusResponse
     */
    public function sendData($data)
    {
        return $this->response = new StatusResponse($this, $data);
    }

    /**
     * Validate status check specific required fields.
     *
     * @return void
     * @throws \InvalidArgumentException When required fields are missing
     */
    protected function validateStatusSpecificFields()
    {
        if (empty($this->getRRN()) && empty($this->getIntRef())) {
            throw new \InvalidArgumentException('Either RRN or INT_REF is required for status checks');
        }
    }

    /**
     * Get the RRN (Retrieval Reference Number).
     *
     * @return string|null The RRN
     */
    public function getRRN()
    {
        return $this->getParameter('rrn');
    }

    /**
     * Set the RRN (Retrieval Reference Number).
     *
     * @param string $value The RRN
     * @return $this
     */
    public function setRRN($value)
    {
        return $this->setParameter('rrn', $value);
    }

    /**
     * Get the internal reference number.
     *
     * @return string|null The internal reference number
     */
    public function getIntRef()
    {
        return $this->getParameter('intRef');
    }

    /**
     * Set the internal reference number.
     *
     * @param string $value The internal reference number
     * @return $this
     */
    public function setIntRef($value)
    {
        return $this->setParameter('intRef', $value);
    }
}
