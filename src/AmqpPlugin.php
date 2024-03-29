<?php

/*
 *  This file is part of the Micro framework package.
 *
 *  (c) Stanislau Komar <kost@micro-php.net>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Micro\Plugin\Amqp;

use Micro\Component\DependencyInjection\Autowire\AutowireHelperFactory;
use Micro\Component\DependencyInjection\Autowire\AutowireHelperFactoryInterface;
use Micro\Component\DependencyInjection\Container;
use Micro\Framework\Kernel\Plugin\ConfigurableInterface;
use Micro\Framework\Kernel\Plugin\DependencyProviderInterface;
use Micro\Framework\Kernel\Plugin\PluginConfigurationTrait;
use Micro\Framework\Kernel\Plugin\PluginDependedInterface;
use Micro\Plugin\Amqp\Business\Consumer\Locator\ConsumerLocatorFactory;
use Micro\Plugin\Amqp\Business\Consumer\Locator\ConsumerLocatorFactoryInterface;
use Micro\Plugin\Amqp\Facade\AmqpFacade;
use Micro\Plugin\Amqp\Facade\AmqpFacadeInterface;
use Micro\Plugin\Console\ConsolePlugin;
use Micro\Plugin\Locator\Facade\LocatorFacadeInterface;
use Micro\Plugin\Uuid\UuidFacadeInterface;
use Micro\Plugin\Uuid\UuidPlugin;

/**
 * @method AmqpPluginConfiguration configuration()
 */
class AmqpPlugin implements DependencyProviderInterface, PluginDependedInterface, ConfigurableInterface
{
    use PluginConfigurationTrait;

    private UuidFacadeInterface $uuidFacade;

    private AutowireHelperFactoryInterface $autowireHelperFactory;

    private LocatorFacadeInterface $locatorFacade;

    /**
     * {@inheritDoc}
     */
    public function provideDependencies(Container $container): void
    {
        $container->register(
            AmqpFacadeInterface::class, function (
                UuidFacadeInterface $uuidFacade,
                LocatorFacadeInterface $locatorFacade
            ) use ($container) {
                $this->uuidFacade = $uuidFacade;
                $this->locatorFacade = $locatorFacade;
                $this->autowireHelperFactory = new AutowireHelperFactory($container);

                return $this->createAmqpFacade();
            }
        );
    }

    protected function createAmqpFacade(): AmqpFacadeInterface
    {
        return new AmqpFacade(
            $this->createPluginComponentBuilder(),
            $this->createConsumerLocatorFactory(),
            $this->configuration()
        );
    }

    public function createConsumerLocatorFactory(): ConsumerLocatorFactoryInterface
    {
        return new ConsumerLocatorFactory($this->locatorFacade);
    }

    protected function createPluginComponentBuilder(): PluginComponentBuilderInterface
    {
        return new PluginComponentBuilder(
            $this->configuration(),
            $this->autowireHelperFactory,
            $this->uuidFacade,
        );
    }

    public function getDependedPlugins(): iterable
    {
        return [
            ConsolePlugin::class,
            UuidPlugin::class,
        ];
    }
}
