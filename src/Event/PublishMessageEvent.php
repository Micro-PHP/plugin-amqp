<?php

namespace Micro\Plugin\Amqp\Event;

use Micro\Plugin\Amqp\Business\Message\MessageInterface;
use Micro\Plugin\Amqp\Business\Publisher\PublisherConfigurationInterface;

class PublishMessageEvent extends AbstractActionMessageEvent implements PublishMessageEventInterface
{
    /**
     * @param MessageInterface $message
     * @param PublisherConfigurationInterface $publisherConfiguration
     */
    public function __construct(
        MessageInterface $message,
        private PublisherConfigurationInterface $publisherConfiguration
    )
    {
        parent::__construct($message);
    }

    /**
     * {@inheritDoc}
     */
    public function getExchange(): string
    {
        return $this->publisherConfiguration->getExchange();
    }

    /**
     * {@inheritDoc}
     */
    public function getChannel(): string
    {
        return $this->publisherConfiguration->getChannel();
    }

    /**
     * {@inheritDoc}
     */
    public function getConnection(): string
    {
        return $this->publisherConfiguration->getConnection();
    }

    /**
     * {@inheritDoc}
     */
    public function getContentType(): string
    {
        return $this->publisherConfiguration->getContentType();
    }

    /**
     * {@inheritDoc}
     */
    public function getDeliveryMode(): int
    {
        return $this->publisherConfiguration->getDeliveryMode();
    }
}
