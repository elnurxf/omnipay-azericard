<?php

namespace Omnipay\AzeriCard\Message;

use Omnipay\AzeriCard\Constants;

class RefundRequest extends AbstractRequest
{
    /**
     * Get the refund request data.
     *
     * @return array The request data
     * @throws \InvalidArgumentException When validation fails
     */
    public function getData()
    {
        $this->validate('amount', 'transactionId', 'terminalId');
        $this->validateRefundSpecificFields();

        $timestamp = $this->generateTimestamp();
        $nonce     = $this->generateNonce();
        $amount    = $this->formatAmount($this->getAmount());

        $data = [
            'AMOUNT'        => $amount,
            'CURRENCY'      => $this->getCurrency() ?: Constants::CURRENCY_AZN,
            'ORDER'         => $this->getTransactionId(),
            'RRN'           => $this->getRRN(),
            'INT_REF'       => $this->getIntRef(),
            'TERMINAL'      => $this->getTerminalId(),
            'TRTYPE'        => Constants::TRTYPE_REFUND,
            'TIMESTAMP'     => $timestamp,
            'NONCE'         => $nonce,
            'MAC_KEY_INDEX' => 0,
        ];

        $data['P_SIGN'] = $this->sign([
            $data['TERMINAL'],
            $data['TRTYPE'],
            $data['AMOUNT'],
            $data['CURRENCY'],
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
     * @return RefundResponse
     */
    public function sendData($data)
    {
        return $this->response = new RefundResponse($this, $data);
    }

    /**
     * Validate refund specific required fields.
     *
     * @return void
     * @throws \InvalidArgumentException When required fields are missing
     */
    protected function validateRefundSpecificFields()
    {
        if (empty($this->getRRN())) {
            throw new \InvalidArgumentException('RRN is required for refund transactions');
        }

        if (empty($this->getIntRef())) {
            throw new \InvalidArgumentException('INT_REF is required for refund transactions');
        }
    }

}
