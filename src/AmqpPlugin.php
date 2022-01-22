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
use Micro\Plugin\Amqp\Business\Consumer\ConsumerManagerInterface;
use Micro\Plugin\Amqp\Business\EventLisneter\TerminateApplicationEventListener;
use Micro\Plugin\Amqp\Business\Exchange\ExchangeManager;
use Micro\Plugin\Amqp\Business\Exchange\ExchangeManagerInterface;
use Micro\Plugin\Amqp\Business\Message\MessagePublisherInterface;
use Micro\Plugin\Amqp\Business\Publisher\MessagePublisherManager;
use Micro\Plugin\Amqp\Business\Publisher\MessagePublisherManagerInterface;
use Micro\Plugin\Amqp\Business\Queue\QueueManager;
use Micro\Plugin\Amqp\Business\Queue\QueueManagerInterface;
use Micro\Plugin\Logger\LoggerPlugin;


class AmqpPlugin extends AbstractPlugin implements ApplicationListenerProviderPluginInterface
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
            //$this->createConsumerManager($container),
            $this->createMessagePublisherManager($container)
        );
    }

    /**
     * @param  Container $container
     * @return ConsumerManagerInterface
     */
    protected function createConsumerManager(Container $container): ConsumerManagerInterface
    {

    }

    /**
     * @param  Container $container
     * @return MessagePublisherInterface
     */
    protected function createMessagePublisherManager(Container $container): MessagePublisherManagerInterface
    {
        return new MessagePublisherManager(
            $this->channelManager,
            $this->configuration
        );
    }
}
