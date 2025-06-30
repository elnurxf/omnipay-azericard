
<?php

namespace Omnipay\AzeriCard;

use Omnipay\Common\AbstractGateway;
use Omnipay\AzeriCard\Message\PurchaseRequest;
use Omnipay\AzeriCard\Message\CompletePurchaseRequest;
use Omnipay\AzeriCard\Message\RefundRequest;
use Omnipay\AzeriCard\Message\StatusRequest;
use Omnipay\AzeriCard\Message\CompleteSaleRequest;

class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'AzeriCard';
    }

    public function getDefaultParameters()
    {
        return [
            'terminalId' => '',
            'privateKeyPath' => '',
            'publicKeyPath' => '',
            'testMode' => true,
        ];
    }

    public function purchase(array $parameters = [])
    {
        return $this->createRequest(PurchaseRequest::class, $parameters);
    }

    public function completePurchase(array $parameters = [])
    {
        return $this->createRequest(CompletePurchaseRequest::class, $parameters);
    }

    public function refund(array $parameters = [])
    {
        return $this->createRequest(RefundRequest::class, $parameters);
    }
    
    public function completeSale(array $parameters = [])
    {
        return $this->createRequest(CompleteSaleRequest::class, $parameters);
    }
    
    public function status(array $parameters = [])
    {
        return $this->createRequest(StatusRequest::class, $parameters);
    }

    // Getter/Setter methods for better IDE support
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
