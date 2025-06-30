<?php

namespace Omnipay\AzeriCard\Message;

use Omnipay\Common\Message\AbstractRequest as BaseRequest;

abstract class AbstractRequest extends BaseRequest
{
    protected function generateTimestamp()
    {
        return gmdate('YmdHis');
    }

    protected function generateNonce($length = 16)
    {
        return bin2hex(random_bytes($length / 2));
    }

    protected function sign(array $fields)
    {
        $source = '';
        foreach ($fields as $value) {
            $source .= strlen($value) . $value;
        }

        openssl_sign(
            $source,
            $signature,
            file_get_contents($this->getParameter('privateKeyPath')),
            OPENSSL_ALGO_SHA256
        );

        return bin2hex($signature);
    }

    public function getEndpoint()
    {
        return $this->getTestMode()
            ? 'https://testmpi.3dsecure.az/cgi-bin/cgi_link'
            : 'https://secure.azericard.com/cgi-bin/cgi_link';
    }
}
