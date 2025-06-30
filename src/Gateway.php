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
    /**
     * Get the gateway display name.
     *
     * @return string The gateway name
     */
    public function getName()
    {
        return 'AzeriCard';
    }

    /**
     * Get the default parameters for this gateway.
     *
     * @return array Default parameters
     */
    public function getDefaultParameters()
    {
        return [
            'terminalId'     => '',
            'privateKeyPath' => '',
            'publicKeyPath'  => '',
            'testMode'       => true,
        ];
    }

    /**
     * Create an authorize request.
     *
     * @param array $parameters Request parameters
     * @return \Omnipay\Common\Message\RequestInterface
     */
    public function authorize(array $parameters = [])
    {
        return $this->createRequest(AuthorizeRequest::class, $parameters);
    }

    /**
     * Create a purchase request.
     *
     * @param array $parameters Request parameters
     * @return \Omnipay\Common\Message\RequestInterface
     */
    public function purchase(array $parameters = [])
    {
        return $this->createRequest(PurchaseRequest::class, $parameters);
    }

    /**
     * Create a complete purchase request.
     *
     * @param array $parameters Request parameters
     * @return \Omnipay\Common\Message\RequestInterface
     */
    public function completePurchase(array $parameters = [])
    {
        return $this->createRequest(CompletePurchaseRequest::class, $parameters);
    }

    /**
     * Create a refund request.
     *
     * @param array $parameters Request parameters
     * @return \Omnipay\Common\Message\RequestInterface
     */
    public function refund(array $parameters = [])
    {
        return $this->createRequest(RefundRequest::class, $parameters);
    }

    /**
     * Create a complete sale request.
     *
     * @param array $parameters Request parameters
     * @return \Omnipay\Common\Message\RequestInterface
     */
    public function completeSale(array $parameters = [])
    {
        return $this->createRequest(CompleteSaleRequest::class, $parameters);
    }

    /**
     * Create a status check request.
     *
     * @param array $parameters Request parameters
     * @return \Omnipay\Common\Message\RequestInterface
     */
    public function status(array $parameters = [])
    {
        return $this->createRequest(StatusRequest::class, $parameters);
    }

    /**
     * Create a void request.
     *
     * @param array $parameters Request parameters
     * @return \Omnipay\Common\Message\RequestInterface
     */
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

    public function getCurrency()
    {
        return $this->getParameter('currency');
    }

    public function setCurrency($value)
    {
        return $this->setParameter('currency', $value);
    }

    public function getCountry()
    {
        return $this->getParameter('country');
    }

    public function setCountry($value)
    {
        return $this->setParameter('country', $value);
    }

    public function getLang()
    {
        return $this->getParameter('lang');
    }

    public function setLang($value)
    {
        return $this->setParameter('lang', $value);
    }

    public function getMerchGmt()
    {
        return $this->getParameter('merchGmt');
    }

    public function setMerchGmt($value)
    {
        return $this->setParameter('merchGmt', $value);
    }

    public function getMerchName()
    {
        return $this->getParameter('merchName');
    }

    public function setMerchName($value)
    {
        return $this->setParameter('merchName', $value);
    }

    public function getMerchUrl()
    {
        return $this->getParameter('merchUrl');
    }

    public function setMerchUrl($value)
    {
        return $this->setParameter('merchUrl', $value);
    }

    public function getEmail()
    {
        return $this->getParameter('email');
    }

    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
    }

    public function getReturnUrl()
    {
        return $this->getParameter('returnUrl');
    }

    public function setReturnUrl($value)
    {
        return $this->setParameter('returnUrl', $value);
    }

    public function getDescription()
    {
        return $this->getParameter('description');
    }

    public function setDescription($value)
    {
        return $this->setParameter('description', $value);
    }

    public function getCustomerName()
    {
        return $this->getParameter('name');
    }

    public function setCustomerName($value)
    {
        return $this->setParameter('name', $value);
    }

    public function getMInfo()
    {
        return $this->getParameter('mInfo');
    }

    public function setMInfo($value)
    {
        return $this->setParameter('mInfo', $value);
    }
}
