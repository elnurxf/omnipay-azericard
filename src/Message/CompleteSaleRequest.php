<?php

namespace Omnipay\AzeriCard\Message;

use Omnipay\AzeriCard\Constants;

class CompleteSaleRequest extends AbstractRequest
{
    /**
     * Get the complete sale request data.
     *
     * @return array The request data
     * @throws \InvalidArgumentException When validation fails
     */
    public function getData()
    {
        $this->validate('amount', 'transactionId', 'terminalId');
        $this->validateCompleteSaleSpecificFields();

        $timestamp = $this->generateTimestamp();
        $nonce     = $this->generateNonce();
        $amount    = $this->formatAmount($this->getAmount());

        $data = [
            'AMOUNT'        => $amount,
            'CURRENCY'      => Constants::CURRENCY_AZN,
            'ORDER'         => $this->getTransactionId(),
            'RRN'           => $this->getRRN(),
            'INT_REF'       => $this->getIntRef(),
            'TERMINAL'      => $this->getTerminalId(),
            'TRTYPE'        => Constants::TRTYPE_COMPLETE_SALE,
            'TIMESTAMP'     => $timestamp,
            'NONCE'         => $nonce,
            'MAC_KEY_INDEX' => 0,
        ];

        $data['P_SIGN'] = $this->sign([
            $amount,
            $data['CURRENCY'],
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
     * @return CompleteSaleResponse
     */
    public function sendData($data)
    {
        return $this->response = new CompleteSaleResponse($this, $data);
    }

    /**
     * Validate complete sale specific required fields.
     *
     * @return void
     * @throws \InvalidArgumentException When required fields are missing
     */
    protected function validateCompleteSaleSpecificFields()
    {
        if (empty($this->getRRN())) {
            throw new \InvalidArgumentException('RRN is required for completing sales');
        }

        if (empty($this->getIntRef())) {
            throw new \InvalidArgumentException('INT_REF is required for completing sales');
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
