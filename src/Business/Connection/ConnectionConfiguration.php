<?php

namespace Micro\Plugin\Amqp\Business\Connection;

use Micro\Plugin\Amqp\AbstractAmqpComponentConfiguration;

class ConnectionConfiguration extends AbstractAmqpComponentConfiguration implements ConnectionConfigurationInterface
{
    private const CFG_HOST          = 'AMQP_CONNECTION_%s_HOST';
    private const CFG_PORT          = 'AMQP_CONNECTION_%s_PORT';
    private const CFG_LOGIN         = 'AMQP_CONNECTION_%sLOGIN';
    private const CFG_PASSWORD      = 'AMQP_CONNECTION_%s_PASSWORD';
    private const CFG_TIMEOUT_READ  = 'AMQP_CONNECTION_%s_TIMEOUT_READ';
    private const CFG_TIMEOUT_WRITE = 'AMQP_CONNECTION_%s_TIMEOUT_WRITE';
    private const CFG_TIMEOUT_RPC   = 'AMQP_CONNECTION_%s_TIMEOUT_RPC';
    private const CFG_SASL_METHOD   = 'AMQP_CONNECTION_%s_SASL_METHOD';
    private const CFG_CA_CERT       = 'AMQP_CONNECTION_%s_CA_CERT';
    private const CFG_CERT          = 'AMQP_CONNECTION_%s_CERT';
    private const CFG_KEY           = 'AMQP_CONNECTION_%s_KEY';
    private const CFG_VERIFY        = 'AMQP_CONNECTION_%s_VERIFY';
    private const CFG_VIRTUAL_HOST  = 'AMQP_CONNECTION_%s_VHOST';
    private const CFG_LOCALE        = 'AMQP_CONNECTION_%s_LOCALE';
    private const CFG_KEEPALIVE     = 'AMQP_CONNECTION_%s_KEEPALIVE';
    private const CFG_SSL_ENABLED   = 'AMQP_CONNECTION_%s_SSL_ENABLED';
    private const CFG_SSL_PROTOCOL  = 'AMQP_CONNECTION_%s_SSL_PROTOCOL';

    /**
     * {@inheritDoc}
     */
    public function getHost(): string
    {
        return $this->get(self::CFG_HOST, 'localhost');
    }

    /**
     * {@inheritDoc}
     */
    public function getPort(): int
    {
        return (int)$this->get(self::CFG_PORT, 5672);
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return $this->configRoutingKey;
    }

    /**
     * {@inheritDoc}
     */
    public function getUsername(): string
    {
        return $this->get(self::CFG_LOGIN, 'guest');
    }

    /**
     * {@inheritDoc}
     */
    public function getPassword(): string
    {
        return $this->get(self::CFG_PASSWORD, 'guest');
    }

    /**
     * {@inheritDoc}
     */
    public function getConnectionTimeout(): float
    {
        return (float)$this->get(self::CFG_TIMEOUT_READ);
    }

    /**
     * {@inheritDoc}
     */
    public function getReadWriteTimeout(): float
    {
        return (float)$this->get(self::CFG_TIMEOUT_WRITE);
    }

    /**
     * {@inheritDoc}
     */
    public function getRpcTimeout(): float
    {
        return (float)$this->get(self::CFG_TIMEOUT_RPC);
    }

    /**
     * {@inheritDoc}
     */
    public function getSaslMethod(): string
    {
        return $this->get(self::CFG_SASL_METHOD, 'AMQPLAIN');
    }

    /**
     * @return string|null
     */
    public function getCaCert(): ?string
    {
        return $this->get(self::CFG_CA_CERT);
    }

    /**
     * {@inheritDoc}
     */
    public function getCert(): ?string
    {
        return $this->get(self::CFG_CERT);
    }

    /**
     * Get path to the client key in PEM format
     *
     * {@inheritDoc}
     */
    public function getKey(): ?string
    {
        return $this->get(self::CFG_KEY);
    }

    /**
     * {@inheritDoc}
     */
    public function isVerify(): bool
    {
        return (bool)$this->get(self::CFG_VERIFY, false);
    }

    /**
     * {@inheritDoc}
     */
    public function getVirtualHost(): string
    {
        return $this->get(self::CFG_VIRTUAL_HOST, '/');
    }

    /**
     * {@inheritDoc}
     */
    public function getLocale(): string
    {
        return $this->get(self::CFG_LOCALE, 'en_US');
    }

    /**
     * {@inheritDoc}
     */
    public function keepAlive(): bool
    {
        return $this->get(self::CFG_KEEPALIVE, false);
    }

    /**
     * {@inheritDoc}
     */
    public function sslEnabled(): bool
    {
        return $this->get(self::CFG_SSL_ENABLED, false);
    }

    /**
     * {@inheritDoc}
     */
    public function getSslProtocol(): string
    {
        return $this->get(self::CFG_SSL_PROTOCOL, 'ssl');
    }

    /**
     * {@inheritDoc}
     */
    public function validateSslConfiguration(): void
    {
        $options = [
            $this->cfg(self::CFG_SSL_PROTOCOL) => $this->getSslProtocol(),
            $this->cfg(self::CFG_CERT)  => $this->getCert(),
            $this->cfg(self::CFG_CA_CERT) => $this->getCaCert(),
        ];

        $invalidKeys = [];

        foreach ($options as $option) {
            if(empty($options[$option])) {
                $invalidKeys[] = $option;
            }
        }

        if(count($invalidKeys) > 0) {
            throw new \InvalidArgumentException(
                sprintf('Invalid SSL configuration. Please, define these environment variables: %s', implode(', ', $invalidKeys))
            );
        }
    }
}
