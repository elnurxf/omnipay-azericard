
<?php

namespace Omnipay\AzeriCard\Message;

use Omnipay\Common\Message\AbstractRequest as BaseRequest;
use Omnipay\AzeriCard\Constants;

abstract class AbstractRequest extends BaseRequest
{
    protected function generateTimestamp()
    {
        return gmdate('YmdHis');
    }

    protected function generateNonce($length = 16)
    {
        if ($length % 2 !== 0) {
            throw new \InvalidArgumentException('Nonce length must be even');
        }
        return bin2hex(random_bytes($length / 2));
    }

    protected function sign(array $fields)
    {
        $privateKeyPath = $this->getParameter('privateKeyPath');
        $this->validatePrivateKey($privateKeyPath);

        $source = $this->buildSignatureSource($fields);
        $privateKey = $this->loadPrivateKey($privateKeyPath);
        
        $result = openssl_sign($source, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        
        if ($result === false) {
            throw new \RuntimeException('Failed to sign data: ' . openssl_error_string());
        }

        return bin2hex($signature);
    }
    
    protected function validatePrivateKey($privateKeyPath)
    {
        if (empty($privateKeyPath)) {
            throw new \InvalidArgumentException('Private key path not specified');
        }
        
        if (!file_exists($privateKeyPath)) {
            throw new \InvalidArgumentException('Private key file not found: ' . $privateKeyPath);
        }
        
        if (!is_readable($privateKeyPath)) {
            throw new \InvalidArgumentException('Private key file is not readable: ' . $privateKeyPath);
        }
    }
    
    protected function loadPrivateKey($privateKeyPath)
    {
        $privateKey = file_get_contents($privateKeyPath);
        if ($privateKey === false) {
            throw new \RuntimeException('Failed to read private key file: ' . $privateKeyPath);
        }
        return $privateKey;
    }
    
    protected function buildSignatureSource(array $fields)
    {
        $source = '';
        foreach ($fields as $value) {
            $source .= strlen($value) . $value;
        }
        return $source;
    }
    
    protected function formatAmount($amount)
    {
        return number_format((float)$amount, 2, '.', '');
    }

    public function getEndpoint()
    {
        return $this->getTestMode()
            ? 'https://testmpi.3dsecure.az/cgi-bin/cgi_link'
            : 'https://secure.azericard.com/cgi-bin/cgi_link';
    }
    
    public function getTerminalId()
    {
        return $this->getParameter('terminalId');
    }
    
    public function setTerminalId($value)
    {
        return $this->setParameter('terminalId', $value);
    }
    
    public function getPrivateKeyPath()
    {
        return $this->getParameter('privateKeyPath');
    }
    
    public function setPrivateKeyPath($value)
    {
        return $this->setParameter('privateKeyPath', $value);
    }
    
    public function getPublicKeyPath()
    {
        return $this->getParameter('publicKeyPath');
    }
    
    public function setPublicKeyPath($value)
    {
        return $this->setParameter('publicKeyPath', $value);
    }
}
