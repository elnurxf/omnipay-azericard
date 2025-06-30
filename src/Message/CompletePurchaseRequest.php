<?php

namespace Omnipay\AzeriCard\Message;

class CompletePurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        return $this->httpRequest->request->all();
    }

    public function sendData($data)
    {
        return $this->response = new CompletePurchaseResponse($this, $data);
    }


    protected function verifySignature(array $data)
    {
        $fields = [
            $data['AMOUNT'] ?? '-',
            $data['TERMINAL'] ?? '-',
            $data['APPROVAL'] ?? '-',
            $data['RRN'] ?? '-',
            $data['INT_REF'] ?? '-',
        ];

        $source = '';
        foreach ($fields as $field) {
            if ($field === '-') {
                $source .= '-';
            } else {
                $source .= strlen($field) . $field;
            }
        }

        $signature = hex2bin($data['P_SIGN'] ?? '');
        $publicKey = file_get_contents($this->getParameter('publicKeyPath'));

        return openssl_verify($source, $signature, $publicKey, OPENSSL_ALGO_SHA256) === 1;
    }

}