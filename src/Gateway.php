<?php

namespace Omnipay\AzeriCard;

use Omnipay\Common\AbstractGateway;

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
            'testMode' => true,
        ];
    }

    public function purchase(array $parameters = [])
    {
        return $this->createRequest(\Omnipay\AzeriCard\Message\PurchaseRequest::class, $parameters);
    }

    public function completePurchase(array $parameters = [])
    {
        return $this->createRequest(\Omnipay\AzeriCard\Message\CompletePurchaseRequest::class, $parameters);
    }

    public function refund(array $parameters = [])
    {
        return $this->createRequest(\Omnipay\AzeriCard\Message\RefundRequest::class, $parameters);
    }
}
