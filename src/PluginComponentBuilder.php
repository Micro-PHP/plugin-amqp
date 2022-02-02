<?php

namespace Micro\Plugin\Amqp;

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
use Micro\Plugin\EventEmitter\EventsFacadeInterface;

/**
 * @TODO: Need to be refactoring
 */
class PluginComponentBuilder implements PluginComponentBuilderInterface
{
    /**
     * @var ConnectionManagerInterface|null
     */
    protected ?ConnectionManagerInterface $connectionManager;

    /**
     * @var ChannelManagerInterface|null
     */
    protected ?ChannelManagerInterface $channelManager;

    /**
     * @var ExchangeManagerInterface|null
     */
    protected ?ExchangeManagerInterface $exchangeManager;

    /**
     * @var QueueManager
     */
    protected QueueManager $queueManager;

    /**
     * @var bool
     */
    private bool $initialized;

    /**
     * @param AmqpPluginConfiguration $configuration
     * @param MessageSerializerFactoryInterface $messageSerializerFactory
     * @param EventsFacadeInterface $eventsFacade
     */
    public function __construct(
    private AmqpPluginConfiguration $configuration,
    private MessageSerializerFactoryInterface $messageSerializerFactory,
    private EventsFacadeInterface $eventsFacade
    )
    {
        $this->initialized = false;
        // TODO: Factory for each manager
        $this->connectionManager = new ConnectionManager($this->configuration, new ConnectionBuilder());
        $this->channelManager    = new ChannelManager($this->connectionManager);
        $this->queueManager      = new QueueManager($this->channelManager, $this->configuration);
        $this->exchangeManager   = new ExchangeManager($this->channelManager, $this->configuration);
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
            $this->messageSerializerFactory,
            $this->eventsFacade
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
            $this->messageSerializerFactory,
            $this->eventsFacade
        );
    }
}
