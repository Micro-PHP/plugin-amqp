<?php

namespace Micro\Plugin\Amqp;

use Micro\Plugin\Amqp\Business\Consumer\ConsumerInterface;
use Micro\Plugin\Amqp\Business\Consumer\ConsumerManagerInterface;
use Micro\Plugin\Amqp\Business\Message\MessageInterface;
use Micro\Plugin\Amqp\Business\Publisher\MessagePublisherManagerInterface;

class AmqpFacade implements AmqpFacadeInterface
{
    /**
     * @param ConsumerManagerInterface         $consumerManager
     * @param MessagePublisherManagerInterface $publisherManager
     */
    public function __construct(
        // private ConsumerManagerInterface $consumerManager,
    private MessagePublisherManagerInterface $publisherManager
    ) {
    }

    /**
     * @param  ConsumerInterface $consumer
     * @return void
     */
    public function consume(ConsumerInterface $consumer): void
    {
        // $this->consumerManager->consume($consumer);
    }

    /**
     * @param  MessageInterface $message
     * @return void
     */
    public function publish(MessageInterface $message, string $publisherName = AmqpPluginConfiguration::PUBLISHER_DEFAULT): void
    {
        $this->publisherManager->getPublisher($publisherName)->publish($message);
    }
}
