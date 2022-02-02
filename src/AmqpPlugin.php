<?php

namespace Micro\Plugin\Amqp;

use Micro\Component\DependencyInjection\Container;
use Micro\Component\EventEmitter\EventListenerInterface;
use Micro\Component\EventEmitter\ListenerProviderInterface;
use Micro\Framework\Kernel\Configuration\PluginConfigurationInterface;
use Micro\Framework\Kernel\Plugin\AbstractPlugin;
use Micro\Kernel\App\Business\ApplicationListenerProviderPluginInterface;
use Micro\Plugin\Amqp\Business\EventLisneter\ListenerProvider;
use Micro\Plugin\Amqp\Business\EventLisneter\TerminateApplicationEventListener;
use Micro\Plugin\Amqp\Business\Serializer\MessageSerializerFactory;
use Micro\Plugin\Amqp\Business\Serializer\MessageSerializerFactoryInterface;
use Micro\Plugin\Amqp\Console\ConsumeCommand;
use Micro\Plugin\Amqp\Console\ConsumerListCommand;
use Micro\Plugin\Amqp\Console\PublisherListCommand;
use Micro\Plugin\Console\CommandProviderInterface;
use Micro\Plugin\EventEmitter\EventsFacadeInterface;
use Micro\Plugin\Serializer\SerializerFacadeInterface;
use Symfony\Component\Console\Command\Command;

/**
 * @method AmqpPluginConfiguration configuration()
 */
class AmqpPlugin extends AbstractPlugin implements ApplicationListenerProviderPluginInterface, CommandProviderInterface
{
    /**
     * @var Container|null
     */
    private ?Container $container = null;

    /**
     * {@inheritDoc}
     */
    public function provideDependencies(Container $container): void
    {
        $this->container = $container;

        $container->register(
            AmqpFacadeInterface::class, function (Container $container) {
                return $this->createAmqpFacade();
            }
        );
    }

    /**
     * {@inheritDoc}
     */
    public function provideConsoleCommands(Container $container): array
    {
        return [
            $this->createCommandConsume(),
            $this->createCommandListConsumer(),
            $this->createCommandListPublisher(),
        ];
    }

    /**
     * @return Command
     */
    protected function createCommandListConsumer(): Command
    {
        return new ConsumerListCommand($this->configuration());
    }

    /**
     * @return Command
     */
    protected function createCommandListPublisher(): Command
    {
        return new PublisherListCommand($this->configuration());
    }

    /**
     * @return ConsumeCommand
     */
    protected function createCommandConsume(): Command
    {
        return new ConsumeCommand($this->container->get(AmqpFacadeInterface::class));
    }

    /**
     * {@inheritDoc}
     */
    public function getEventListenerProvider(): ListenerProviderInterface
    {
        return new ListenerProvider(
            $this->createTerminateEventListener(),
        );
    }

    /**
     * @return EventListenerInterface
     */
    protected function createTerminateEventListener(): EventListenerInterface
    {
        return new TerminateApplicationEventListener($this->container->get(AmqpFacadeInterface::class));
    }

    /**
     * @return AmqpFacadeInterface
     */
    protected function createAmqpFacade(): AmqpFacadeInterface
    {
        return new AmqpFacade(
            $this->createPluginComponentBuilder(),
            $this->createMessageSerializerFactory()
        );
    }

    /**
     * @return PluginComponentBuilderInterface
     */
    protected function createPluginComponentBuilder(): PluginComponentBuilderInterface
    {
        return new PluginComponentBuilder(
            $this->configuration(),
            $this->createMessageSerializerFactory(),
            $this->lookupEventsFacade()
        );
    }

    /**
     * @return EventsFacadeInterface
     */
    protected function lookupEventsFacade(): EventsFacadeInterface
    {
        return $this->container->get(EventsFacadeInterface::class);
    }

    /**
     * @return MessageSerializerFactoryInterface
     */
    protected function createMessageSerializerFactory(): MessageSerializerFactoryInterface
    {
        return new MessageSerializerFactory($this->container->get(SerializerFacadeInterface::class));
    }
}
