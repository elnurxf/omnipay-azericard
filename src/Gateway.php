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

    /**
     * Set the terminal ID.
     *
     * @param string $value The terminal ID
     * @return $this
     */
    public function setTerminalId($value)
    {
        return $this->setParameter('terminalId', $value);
    }

    /**
     * Set the private key file path.
     *
     * @param string $value The private key file path
     * @return $this
     */
    public function setPrivateKeyPath($value)
    {
        return $this->setParameter('privateKeyPath', $value);
    }

    /**
     * Set the public key file path.
     *
     * @param string $value The public key file path
     * @return $this
     */
    public function setPublicKeyPath($value)
    {
        return $this->setParameter('publicKeyPath', $value);
    }

}
