<?php

namespace Micro\Plugin\Amqp\Business\Consumer;

use Micro\Plugin\Amqp\AmqpPluginConfiguration;

interface ConsumerManagerInterface
{
    /**
     * @param string $consumerName
     * @return void
     */
    public function consume(string $consumerName = AmqpPluginConfiguration::CONSUMER_DEFAULT): void;

    /**
     * @param ConsumerProcessorInterface $consumerProcessor
     * @param string $consumerName
     * @return void
     */
    public function registerConsumerProcessor(
        ConsumerProcessorInterface $consumerProcessor,
        string $consumerName = AmqpPluginConfiguration::CONSUMER_DEFAULT
    ): void;
}
