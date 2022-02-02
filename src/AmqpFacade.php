<?php

namespace Micro\Plugin\Amqp;

use Micro\Plugin\Amqp\Business\Consumer\ConsumerProcessorInterface;
use Micro\Plugin\Amqp\Business\Consumer\ConsumerManagerInterface;
use Micro\Plugin\Amqp\Business\Message\MessageInterface;
use Micro\Plugin\Amqp\Business\Publisher\PublisherManagerInterface;
use Micro\Plugin\Amqp\Business\Serializer\MessageSerializerFactoryInterface;

class AmqpFacade implements AmqpFacadeInterface
{
    /**
     * @var ConsumerManagerInterface
     */
    private ConsumerManagerInterface $consumerManager;

    /**
     * @var PublisherManagerInterface
     */
    private PublisherManagerInterface $publisherManager;

    /**
     * @param PluginComponentBuilderInterface $pluginComponentBuilder
     */
    public function __construct(
    private PluginComponentBuilderInterface $pluginComponentBuilder,
    private MessageSerializerFactoryInterface $messageSerializerFactory
    )
    {
        $this->consumerManager  = $this->pluginComponentBuilder->createConsumerManager();
        $this->publisherManager = $this->pluginComponentBuilder->createMessagePublisherManager();
    }

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
        $this->initialize()->consumerManager->consume($consumerName);
    }

    /**
     * {@inheritDoc}
     */
    public function publish(MessageInterface $message, string $publisherName = AmqpPluginConfiguration::PUBLISHER_DEFAULT): void
    {
        $this->initialize()->publisherManager->publish($message, $publisherName);
    }

    /**
     * {@inheritDoc}
     */
    public function terminate(): void
    {
        $this->pluginComponentBuilder->getConnectionManager()->closeConnectionsAll();
    }

    /**
     * @return $this
     */
    protected function initialize(): self
    {
        $this->pluginComponentBuilder->initialize();

        return $this;
    }

    /**
     * @param MessageInterface $message
     *
     * @return string
     */
    public function serializeMessage(MessageInterface $message): string
    {
        return $this->messageSerializerFactory->create()->serialize($message);
    }

    /**
     * @param string $messageContent
     *
     * @return MessageInterface
     */
    public function deserializeMessage(string $messageContent): MessageInterface
    {
        $this->messageSerializerFactory->create()->deserialize($messageContent);
    }
}
