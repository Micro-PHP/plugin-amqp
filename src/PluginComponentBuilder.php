<?php

namespace Micro\Plugin\Amqp;

use Micro\Component\DependencyInjection\Container;
use Micro\Plugin\Amqp\Business\Channel\ChannelManager;
use Micro\Plugin\Amqp\Business\Channel\ChannelManagerInterface;
use Micro\Plugin\Amqp\Business\Connection\ConnectionBuilder;
use Micro\Plugin\Amqp\Business\Connection\ConnectionManager;
use Micro\Plugin\Amqp\Business\Connection\ConnectionManagerInterface;
use Micro\Plugin\Amqp\Business\Consumer\ConsumerManager;
use Micro\Plugin\Amqp\Business\Consumer\ConsumerManagerInterface;
use Micro\Plugin\Amqp\Business\Exchange\ExchangeManager;
use Micro\Plugin\Amqp\Business\Exchange\ExchangeManagerInterface;
use Micro\Plugin\Amqp\Business\Publisher\PublisherFactory;
use Micro\Plugin\Amqp\Business\Publisher\PublisherFactoryInterface;
use Micro\Plugin\Amqp\Business\Publisher\PublisherManager;
use Micro\Plugin\Amqp\Business\Publisher\PublisherManagerInterface;
use Micro\Plugin\Amqp\Business\Queue\QueueManager;
use Micro\Plugin\Amqp\Business\Queue\QueueManagerInterface;
use Micro\Plugin\Amqp\Business\Serializer\MessageSerializerFactoryInterface;
use Psr\Log\LoggerInterface;

class PluginComponentBuilder implements PluginComponentBuilderInterface
{
    /**
     * @var ConnectionManagerInterface|null
     */
    protected ?ConnectionManagerInterface $connectionManager = null;

    /**
     * @var ChannelManagerInterface|null
     */
    protected ?ChannelManagerInterface $channelManager = null;

    /**
     * @var ExchangeManagerInterface|null
     */
    protected ?ExchangeManagerInterface $exchangeManager = null;

    /**
     * @var QueueManagerInterface|null
     */
    protected ?QueueManagerInterface $queueManager = null;

    /**
     * @var bool
     */
    private bool $initialized;

    /**
     * @param AmqpPluginConfiguration $configuration
     * @param MessageSerializerFactoryInterface $messageSerializerFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        private AmqpPluginConfiguration $configuration,
        private MessageSerializerFactoryInterface $messageSerializerFactory,
        private LoggerInterface $logger
    ) {
        $this->initialized = false;
        $this->connectionManager = new ConnectionManager($this->configuration, new ConnectionBuilder(), $this->logger);
        $this->channelManager = new ChannelManager($this->connectionManager, $this->logger);
        $this->queueManager = new QueueManager($this->channelManager, $this->configuration, $this->logger);
        $this->exchangeManager = new ExchangeManager($this->channelManager, $this->configuration, $this->logger);

    }

    /**
     * {@inheritDoc}
     */
    public function initialize(): PluginComponentBuilderInterface
    {
        if($this->initialized) {
            return $this;
        }

        $this->queueManager->configure();
        $this->exchangeManager->configure();
        $this->queueManager->bindings();

        $this->initialized = true;

        return $this;
    }

    /**
     * @return ConnectionManagerInterface
     */
    public function getConnectionManager(): ConnectionManagerInterface
    {
        return $this->connectionManager;
    }

    /**
     * {@inheritDoc}
     */
    public function createConsumerManager(): ConsumerManagerInterface
    {
        return new ConsumerManager(
            $this->configuration,
            $this->channelManager,
            $this->messageSerializerFactory
        );
    }

    /**
     * {@inheritDoc}
     */
    public function createMessagePublisherManager(): PublisherManagerInterface
    {
        return new PublisherManager($this->createPublisherFactory());
    }

    /**
     * @return PublisherFactoryInterface
     */
    protected function createPublisherFactory(): PublisherFactoryInterface
    {
        return new PublisherFactory(
            $this->channelManager,
            $this->configuration,
            $this->messageSerializerFactory
        );
    }
}
