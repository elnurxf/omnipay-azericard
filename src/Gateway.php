<?php

namespace Omnipay\AzeriCard;

use Omnipay\AzeriCard\Message\AuthorizeRequest;
use Omnipay\AzeriCard\Message\CompletePurchaseRequest;
use Omnipay\AzeriCard\Message\CompleteSaleRequest;
use Omnipay\AzeriCard\Message\PurchaseRequest;
use Omnipay\AzeriCard\Message\RefundRequest;
use Omnipay\AzeriCard\Message\StatusRequest;
use Omnipay\AzeriCard\Message\VoidRequest;
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
            'terminalId'     => '',
            'privateKeyPath' => '',
            'publicKeyPath'  => '',
            'testMode'       => true,
        ];
    }

    public function authorize(array $parameters = [])
    {
        return $this->createRequest(AuthorizeRequest::class, $parameters);
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

    public function void(array $parameters = [])
    {
        return $this->createRequest(VoidRequest::class, $parameters);
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

    public function completePurchase(array $options = [])
    {
        return $this->createRequest('\Omnipay\AzeriCard\Message\CompletePurchaseRequest', $options);
    }

    /**
     * Configure gateway for Azerbaijan market defaults
     */
    public function configureForAzerbaijan()
    {
        $this->setParameter('currency', 'AZN');
        $this->setParameter('country', 'AZ');
        $this->setParameter('lang', 'az');
        $this->setParameter('merchGmt', '+4');
        return $this;
    }

    /**
     * Set merchant information in bulk
     */
    public function setMerchantInfo(array $info)
    {
        $mapping = [
            'name'     => 'merchName',
            'url'      => 'merchUrl',
            'email'    => 'email',
            'country'  => 'country',
            'timezone' => 'merchGmt',
            'language' => 'lang',
        ];

        foreach ($mapping as $key => $parameter) {
            if (isset($info[$key])) {
                $this->setParameter($parameter, $info[$key]);
            }
        }

        return $this;
    }
}
