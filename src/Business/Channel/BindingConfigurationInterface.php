<?php

namespace Micro\Plugin\Amqp\Business\Channel;

interface BindingConfigurationInterface
{
    /**
     * @return string
     */
    public function getQueueName(): string;

    /**
     * @return string
     */
    public function getExchangeName(): string;

    /**
     * @return string
     */
    public function getConnection(): string;
}
