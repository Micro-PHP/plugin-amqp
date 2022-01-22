<?php

namespace Micro\Plugin\Amqp\Business\Channel;

class BindingConfiguration implements BindingConfigurationInterface
{
    /**
     * @param string $queue
     * @param string $exchange
     */
    public function __construct(private string $queue, private string $exchange, private string $connection)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function getQueueName(): string
    {
        return $this->queue;
    }

    /**
     * {@inheritDoc}
     */
    public function getExchangeName(): string
    {
        return $this->exchange;
    }

    /**
     * {@inheritDoc}
     */
    public function getConnection(): string
    {
        return $this->connection;
    }
}
