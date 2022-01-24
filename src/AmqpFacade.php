<?php

namespace Micro\Plugin\Amqp;

use Micro\Plugin\Amqp\Business\Consumer\ConsumerProcessorInterface;
use Micro\Plugin\Amqp\Business\Consumer\ConsumerManagerInterface;
use Micro\Plugin\Amqp\Business\Message\MessageInterface;
use Micro\Plugin\Amqp\Business\Publisher\PublisherManagerInterface;

class AmqpFacade implements AmqpFacadeInterface
{
    /**
     * @param ConsumerManagerInterface $consumerManager
     * @param PublisherManagerInterface $publisherManager
     */
    public function __construct(
        private ConsumerManagerInterface $consumerManager,
        private PublisherManagerInterface $publisherManager
    ) {}

    /**
     * {@inheritDoc}
     */
    public function registerConsumerProcessor(
        ConsumerProcessorInterface $consumerProcessor,
        string $consumerName = AmqpPluginConfiguration::CONSUMER_DEFAULT
    ): void
    {
        $this->consumerManager->registerConsumerProcessor($consumerProcessor, $consumerName);
    }

    /**
     * {@inheritDoc}
     */
    public function consume(string $consumerName = AmqpPluginConfiguration::CONSUMER_DEFAULT): void
    {
        $this->consumerManager->consume($consumerName);
    }

    /**
     * {@inheritDoc}
     */
    public function publish(MessageInterface $message, string $publisherName = AmqpPluginConfiguration::PUBLISHER_DEFAULT): void
    {
        $this->publisherManager->publish($message, $publisherName);
    }
}
