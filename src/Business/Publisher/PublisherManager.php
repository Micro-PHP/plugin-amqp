<?php

namespace Micro\Plugin\Amqp\Business\Publisher;

use Micro\Plugin\Amqp\AmqpPluginConfiguration;
use Micro\Plugin\Amqp\Business\Message\MessageInterface;

class PublisherManager implements PublisherManagerInterface
{
    /**
     * @var array
     */
    private array $publisherCollection;

    /**
     * @param PublisherFactoryInterface $publisherFactory
     */
    public function __construct(private PublisherFactoryInterface $publisherFactory)
    {
        $this->publisherCollection = [];
    }

    /**
     * {@inheritDoc}
     */
    public function publish(MessageInterface $message, string $publisherName = AmqpPluginConfiguration::PUBLISHER_DEFAULT): void
    {
        $this->getPublisher($publisherName)->publish($message);
    }

    /**
     * {@inheritDoc}
     */
    protected function getPublisher(string $publisherName): PublisherInterface
    {
        if(!empty($this->publisherCollection[$publisherName])) {
            return $this->publisherCollection[$publisherName];
        }

        return $this->publisherCollection[$publisherName] = $this->publisherFactory->create($publisherName);
    }
}
