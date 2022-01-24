<?php

namespace Micro\Plugin\Amqp;

use Micro\Component\DependencyInjection\Container;
use Micro\Component\EventEmitter\EventListenerInterface;
use Micro\Framework\Kernel\Plugin\AbstractPlugin;
use Micro\Kernel\App\Business\ApplicationListenerProviderPluginInterface;
use Micro\Plugin\Amqp\Business\Channel\ChannelManager;
use Micro\Plugin\Amqp\Business\Channel\ChannelManagerInterface;
use Micro\Plugin\Amqp\Business\Connection\ConnectionBuilder;
use Micro\Plugin\Amqp\Business\Connection\ConnectionManager;
use Micro\Plugin\Amqp\Business\Connection\ConnectionManagerInterface;
use Micro\Plugin\Amqp\Business\Consumer\ConsumerManager;
use Micro\Plugin\Amqp\Business\Consumer\ConsumerManagerInterface;
use Micro\Plugin\Amqp\Business\EventLisneter\TerminateApplicationEventListener;
use Micro\Plugin\Amqp\Business\Exchange\ExchangeManager;
use Micro\Plugin\Amqp\Business\Exchange\ExchangeManagerInterface;
use Micro\Plugin\Amqp\Business\Message\MessagePublisherInterface;
use Micro\Plugin\Amqp\Business\Publisher\PublisherFactory;
use Micro\Plugin\Amqp\Business\Publisher\PublisherFactoryInterface;
use Micro\Plugin\Amqp\Business\Publisher\PublisherManager;
use Micro\Plugin\Amqp\Business\Publisher\PublisherManagerInterface;
use Micro\Plugin\Amqp\Business\Queue\QueueManager;
use Micro\Plugin\Amqp\Business\Queue\QueueManagerInterface;
use Micro\Plugin\Amqp\Business\Serializer\MessageSerializerFactory;
use Micro\Plugin\Amqp\Business\Serializer\MessageSerializerFactoryInterface;
use Micro\Plugin\Amqp\Console\ConsumeCommand;
use Micro\Plugin\Console\CommandProviderInterface;
use Micro\Plugin\Logger\LoggerPlugin;
use Micro\Plugin\Serializer\SerializerFacadeInterface;


class AmqpPlugin extends AbstractPlugin implements ApplicationListenerProviderPluginInterface, CommandProviderInterface
{
    /**
     * @var Container
     */
    protected Container $container;

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
     * {@inheritDoc}
     */
    public function provideDependencies(Container $container): void
    {
        $this->container = $container;

        $container->register(
            AmqpFacadeInterface::class, function (Container $container) {
                return $this->createAmqpFacade($container);
            }
        );
    }

    /**
     * {@inheritDoc}
     */
    public function provideCommands(Container $container): array
    {
        return [
            $this->createCommandConsume($container)
        ];
    }

    /**
     * @param Container $container
     * @return ConsumeCommand
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function createCommandConsume(Container $container): ConsumeCommand
    {
        return new ConsumeCommand($container->get(AmqpFacadeInterface::class));
    }

    /**
     * {@inheritDoc}
     */
    public function provideEventListeners(): array
    {
        return [
            $this->createTerminateEventListener(),
        ];
    }

    /**
     * @return EventListenerInterface
     */
    protected function createTerminateEventListener(): EventListenerInterface
    {
        return new TerminateApplicationEventListener($this->connectionManager);
    }

    /**
     * @param  Container $container
     * @return void
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function initCoreManagers(Container $container): void
    {
        $logger = $container->get(LoggerPlugin::SERVICE_LOGGER);

        $this->connectionManager = new ConnectionManager($this->configuration, new ConnectionBuilder(), $logger);
        $this->channelManager = new ChannelManager($this->connectionManager, $logger);
        $this->queueManager = new QueueManager($this->channelManager, $this->configuration, $logger);
        $this->exchangeManager = new ExchangeManager($this->channelManager, $this->configuration, $logger);

        $this->queueManager->configure();
        $this->exchangeManager->configure();
        $this->queueManager->bindings();
    }

    /**
     * @param  Container $container
     * @return AmqpFacadeInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function createAmqpFacade(Container $container): AmqpFacadeInterface
    {
        $this->initCoreManagers($container);

        return new AmqpFacade(
            $this->createConsumerManager($container),
            $this->createMessagePublisherManager($container)
        );
    }

    /**
     * @param Container $container
     * @return ConsumerManagerInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function createConsumerManager(Container $container): ConsumerManagerInterface
    {
        return new ConsumerManager(
            $this->configuration,
            $this->channelManager,
            $this->createMessageSerializerFactory($container)
        );
    }

    /**
     * @param  Container $container
     * @return MessagePublisherInterface
     */
    protected function createMessagePublisherManager(Container $container): PublisherManagerInterface
    {
        return new PublisherManager($this->createPublisherFactory($container));
    }

    /**
     * @param Container $container
     * @return MessageSerializerFactoryInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function createMessageSerializerFactory(Container $container): MessageSerializerFactoryInterface
    {
        return new MessageSerializerFactory($container->get(SerializerFacadeInterface::class));
    }

    /**
     * @param Container $container
     * @return PublisherFactoryInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function createPublisherFactory(Container $container): PublisherFactoryInterface
    {
        return new PublisherFactory(
            $this->channelManager,
            $this->configuration,
            $this->createMessageSerializerFactory($container)
        );
    }
}
