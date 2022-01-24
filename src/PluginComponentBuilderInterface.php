<?php

namespace Micro\Plugin\Amqp;

use Micro\Plugin\Amqp\Business\Connection\ConnectionManagerInterface;
use Micro\Plugin\Amqp\Business\Consumer\ConsumerManagerInterface;
use Micro\Plugin\Amqp\Business\Publisher\PublisherManagerInterface;

interface PluginComponentBuilderInterface
{
    /**
     * @return $this
     */
    public function initialize(): self;

    /**
     * @return ConsumerManagerInterface
     */
    public function createConsumerManager(): ConsumerManagerInterface;

    /**
     * @return PublisherManagerInterface
     */
    public function createMessagePublisherManager(): PublisherManagerInterface;

    /**
     * @return ConnectionManagerInterface
     */
    public function getConnectionManager(): ConnectionManagerInterface;
}
