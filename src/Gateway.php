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

/**
 * AzeriCard Gateway for Omnipay.
 *
 * @method \Omnipay\AzeriCard\Message\AuthorizeRequest authorize(array $options = [])
 * @method \Omnipay\AzeriCard\Message\PurchaseRequest purchase(array $options = [])
 * @method \Omnipay\AzeriCard\Message\RefundRequest refund(array $options = [])
 * @method \Omnipay\AzeriCard\Message\CompletePurchaseRequest completePurchase(array $options = [])
 * @method \Omnipay\AzeriCard\Message\CompleteSaleRequest completeSale(array $options = [])
 * @method \Omnipay\AzeriCard\Message\StatusRequest status(array $options = [])
 * @method \Omnipay\AzeriCard\Message\VoidRequest void(array $options = [])
 */
class Gateway extends AbstractGateway
{
    /**
     * Get the gateway display name.
     *
     * @return string
     */
    public function getName()
    {
        return 'AzeriCard';
    }

    /**
     * Get the default parameters for this gateway.
     *
     * @return array
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
     * Initiate a 3D-Secure authorize (pre-auth) request.
     *
     * @param array $parameters Request parameters
     * @return \Omnipay\Common\Message\RequestInterface
     */
    public function authorize(array $parameters = [])
    {
        return $this->createRequest(AuthorizeRequest::class, $parameters);
    }

    /**
     * Initiate a purchase request.
     *
     * @param array $parameters Request parameters
     * @return \Omnipay\Common\Message\RequestInterface
     */
    public function purchase(array $parameters = [])
    {
        return $this->createRequest(PurchaseRequest::class, $parameters);
    }

    /**
     * Initiate a refund request.
     *
     * @param array $parameters Request parameters
     * @return \Omnipay\Common\Message\RequestInterface
     */
    public function refund(array $parameters = [])
    {
        return $this->createRequest(RefundRequest::class, $parameters);
    }

    /**
     * Complete a purchase request (handle callback).
     *
     * @param array $parameters Request parameters
     * @return \Omnipay\Common\Message\RequestInterface
     */
    public function completePurchase(array $parameters = [])
    {
        return $this->createRequest(CompletePurchaseRequest::class, $parameters);
    }

    /**
     * Complete a sale request (handle callback).
     *
     * @param array $parameters Request parameters
     * @return \Omnipay\Common\Message\RequestInterface
     */
    public function completeSale(array $parameters = [])
    {
        return $this->createRequest(CompleteSaleRequest::class, $parameters);
    }

    /**
     * Initiate a status check request.
     *
     * @param array $parameters Request parameters
     * @return \Omnipay\Common\Message\RequestInterface
     */
    public function status(array $parameters = [])
    {
        return $this->createRequest(StatusRequest::class, $parameters);
    }

    /**
     * Initiate a void request.
     *
     * @param array $parameters Request parameters
     * @return \Omnipay\Common\Message\RequestInterface
     */
    public function void(array $parameters = [])
    {
        return $this->createRequest(VoidRequest::class, $parameters);
    }

    // -------------------------------------------------------------------------
    // Setters and getters for gateway-level parameters
    // -------------------------------------------------------------------------

    /**
     * Get the terminal ID.
     *
     * @return string|null
     */
    public function getTerminalId()
    {
        return $this->getParameter('terminalId');
    }

    /**
     * Set the terminal ID.
     *
     * @param string $value
     * @return $this
     */
    public function setTerminalId($value)
    {
        return $this->setParameter('terminalId', $value);
    }

    /**
     * Get the private key file path.
     *
     * @return string|null
     */
    public function getPrivateKeyPath()
    {
        return $this->getParameter('privateKeyPath');
    }

    /**
     * Set the private key file path.
     *
     * @param string $value
     * @return $this
     */
    public function setPrivateKeyPath($value)
    {
        return $this->setParameter('privateKeyPath', $value);
    }

    /**
     * Get the public key file path.
     *
     * @return string|null
     */
    public function getPublicKeyPath()
    {
        return $this->getParameter('publicKeyPath');
    }

    /**
     * Set the public key file path.
     *
     * @param string $value
     * @return $this
     */
    public function setPublicKeyPath($value)
    {
        return $this->setParameter('publicKeyPath', $value);
    }
}
