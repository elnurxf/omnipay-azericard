<?php

namespace Omnipay\AzeriCard\Message\Requests;

use Omnipay\AzeriCard\Constants;
use Omnipay\AzeriCard\Message\Responses\CompletePurchaseResponse;

/**
 * AzeriCard Completion (Capture/Settle) Request (TRTYPE=21)
 * Sent after customer returns, to finalize the payment.
 */
class CompletePurchaseRequest extends AbstractRequest
{
    /**
     * Get the completion request data.
     *
     * @return array
     */
    public function getData()
    {
        $this->validateRequiredFields([
            'amount',
            'order',
            'terminalId',
            'merchUrl',
        ]);

        $data = [
            'ORDER'     => $this->getOrder(),
            'AMOUNT'    => $this->formatAmount($this->getAmount()),
            'CURRENCY'  => $this->getCurrency() ?: Constants::CURRENCY_AZN,
            'TERMINAL'  => $this->getTerminalId(),
            'TRTYPE'    => Constants::TRTYPE_COMPLETE_AUTH,
            'TIMESTAMP' => $this->getTimestamp() ?: $this->generateTimestamp(),
            'NONCE'     => $this->getNonce() ?: $this->generateNonce(),
            'MERCH_URL' => $this->getMerchUrl(),
        ];

        // Optional documented fields
        if ($this->getDescription())  $data['DESC']       = $this->getDescription();
        if ($this->getEmail())        $data['EMAIL']      = $this->getEmail();
        if ($this->getCustomerName()) $data['NAME']       = $this->getCustomerName();
        if ($this->getMerchName())    $data['MERCH_NAME'] = $this->getMerchName();
        if ($this->getCountry())      $data['COUNTRY']    = $this->getCountry();
        if ($this->getMerchGmt())     $data['MERCH_GMT']  = $this->getMerchGmt();
        if ($this->getLang())         $data['LANG']       = $this->getLang();
        if ($this->getMInfo())        $data['M_INFO']     = $this->getMInfo();
        if ($this->getReturnUrl())    $data['BACKREF']    = $this->getReturnUrl();

        // Signature fields — check the docs for *exact* order and fields!
        $data['P_SIGN'] = $this->sign([
            $data['ORDER'],
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
     * Send the completion (capture) data to AzeriCard.
     *
     * @param array $data
     * @return CompletePurchaseResponse
     */
    public function sendData($data)
    {
        $httpResponse = $this->httpClient->request(
            'POST',
            $this->getEndpoint(),
            ['Content-Type' => 'application/x-www-form-urlencoded'],
            http_build_query($data)
        );

        $body = (string) $httpResponse->getBody();
        $responseData = [];
        parse_str($body, $responseData);

        return $this->response = new CompletePurchaseResponse($this, $responseData);
    }
}
