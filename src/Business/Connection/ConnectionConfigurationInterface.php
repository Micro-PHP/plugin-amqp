<?php

namespace Micro\Plugin\Amqp\Business\Connection;

interface ConnectionConfigurationInterface
{
    /**
     * @return string
     */
    public function getHost(): string;

    /**
     * @return int
     */
    public function getPort(): int;

    /**
     * @return string
     */
    public function getLocale(): string;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getUsername(): string;

    /**
     * @return string
     */
    public function getVirtualHost(): string;

    /**
     * @return string
     */
    public function getPassword(): string;

    /**
     * @return float
     */
    public function getConnectionTimeout(): float;

    /**
     * @return float
     */
    public function getReadWriteTimeout(): float;

    /**
     * @return float
     */
    public function getRpcTimeout(): float;

    /**
     * Get authentication method.
     * RabbitMQ supports multiple SASL authentication mechanisms.
     * There are three such mechanisms built into the server: PLAIN, AMQPLAIN, and RABBIT-CR-DEMO, and one — EXTERNAL — available as a plugin.
     *
     * @@link(https://www.rabbitmq.com/access-control.html#mechanisms)
     *
     * @return string
     */
    public function getSaslMethod(): string;

    /**
     * Get path to the CA cert file in PEM format
     *
     * @return string|null
     */
    public function getCaCert(): ?string;

    /**
     * Get path to the client certificate in PEM format
     *
     * @return string|null
     */
    public function getCert(): ?string;

    /**
     * Get path to the client key in PEM format
     *
     * @return string|null
     */
    public function getKey(): ?string;

    /**
     * @return bool
     */
    public function isVerify(): bool;

    /**
     * @return bool
     */
    public function keepAlive(): bool;

    /**
     * @return bool
     */
    public function sslEnabled(): bool;

    /**
     * @return string
     */
    public function getSslProtocol(): string;

    /**
     * @return void
     * @throws \InvalidArgumentException
     */
    public function validateSslConfiguration(): void;
}
