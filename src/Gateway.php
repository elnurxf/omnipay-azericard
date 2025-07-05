<?php

namespace Omnipay\AzeriCard;

use Omnipay\AzeriCard\Message\Requests\AuthorizeRequest;
use Omnipay\AzeriCard\Message\Requests\CompletePurchaseRequest;
use Omnipay\AzeriCard\Message\Requests\PurchaseRequest;
use Omnipay\AzeriCard\Message\Requests\RefundRequest;
use Omnipay\AzeriCard\Message\Requests\StatusRequest;
use Omnipay\AzeriCard\Message\Requests\VoidRequest;
use Omnipay\Common\AbstractGateway;

/**
 * AzeriCard Gateway for Omnipay.
 *
 * Provides methods for all supported transaction types.
 */
class Gateway extends AbstractGateway
{
    /**
     * Gateway display name.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'AzeriCard';
    }

    /**
     * Default gateway parameters.
     *
     * @return array
     */
    public function getDefaultParameters(): array
    {
        return [
            'terminalId'     => '',
            'privateKeyPath' => '',
            'publicKeyPath'  => '',
            'testMode'       => true,
        ];
    }

    /**
     * Get terminal ID.
     *
     * @return string|null
     */
    public function getTerminalId(): ?string
    {
        return $this->getParameter('terminalId');
    }

    /**
     * Set terminal ID.
     *
     * @param string $value
     * @return $this
     */
    public function setTerminalId(string $value): self
    {
        return $this->setParameter('terminalId', $value);
    }

    /**
     * Get private key file path.
     *
     * @return string|null
     */
    public function getPrivateKeyPath(): ?string
    {
        return $this->getParameter('privateKeyPath');
    }

    /**
     * Set private key file path.
     *
     * @param string $value
     * @return $this
     */
    public function setPrivateKeyPath(string $value): self
    {
        return $this->setParameter('privateKeyPath', $value);
    }

    /**
     * Get public key file path.
     *
     * @return string|null
     */
    public function getPublicKeyPath(): ?string
    {
        return $this->getParameter('publicKeyPath');
    }

    /**
     * Set public key file path.
     *
     * @param string $value
     * @return $this
     */
    public function setPublicKeyPath(string $value): self
    {
        return $this->setParameter('publicKeyPath', $value);
    }

    /**
     * Start a 3D Secure pre-authorization (TRTYPE=1).
     *
     * @param array $parameters
     * @return \Omnipay\Common\Message\RequestInterface
     */
    public function authorize(array $parameters = [])
    {
        return $this->createRequest(AuthorizeRequest::class, $parameters);
    }

    /**
     * Start a direct purchase (TRTYPE=0).
     *
     * @param array $parameters
     * @return \Omnipay\Common\Message\RequestInterface
     */
    public function purchase(array $parameters = [])
    {
        return $this->createRequest(PurchaseRequest::class, $parameters);
    }

    /**
     * Handle gateway callback (usually POST from AzeriCard).
     *
     * @param array $parameters
     * @return \Omnipay\Common\Message\RequestInterface
     */
    public function completePurchase(array $parameters = [])
    {
        return $this->createRequest(CompletePurchaseRequest::class, $parameters);
    }

    /**
     * Refund an authorized transaction (TRTYPE=22).
     *
     * @param array $parameters
     * @return \Omnipay\Common\Message\RequestInterface
     */
    public function refund(array $parameters = [])
    {
        return $this->createRequest(RefundRequest::class, $parameters);
    }

    /**
     * Void (cancel) a transaction (TRTYPE=24).
     *
     * @param array $parameters
     * @return \Omnipay\Common\Message\RequestInterface
     */
    public function void(array $parameters = [])
    {
        return $this->createRequest(VoidRequest::class, $parameters);
    }

    /**
     * Get transaction status (TRTYPE=90).
     *
     * @param array $parameters
     * @return \Omnipay\Common\Message\RequestInterface
     */
    public function status(array $parameters = [])
    {
        return $this->createRequest(StatusRequest::class, $parameters);
    }
}
