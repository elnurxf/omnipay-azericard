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
