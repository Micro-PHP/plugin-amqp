<?php

/*
 *  This file is part of the Micro framework package.
 *
 *  (c) Stanislau Komar <kost@micro-php.net>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Micro\Plugin\Amqp\Configuration\Channel;

use Micro\Plugin\Amqp\AbstractAmqpComponentConfiguration;
use Micro\Plugin\Amqp\Configuration\Binding\BindingConfiguration;
use Micro\Plugin\Amqp\Configuration\Binding\BindingConfigurationInterface;

class ChannelConfiguration extends AbstractAmqpComponentConfiguration implements ChannelConfigurationInterface
{
    private const CFG_BINDINGS = 'AMQP_CHANNEL_%s_BINDINGS';

    private const CFG_QOS_GLOBAL = 'AMQP_CHANNEL_%s_QOS_IS_GLOBAL';

    private const CFG_QOS_PREFETCH_SIZE = 'AMQP_CHANNEL_%s_QOS_PREFETCH_SIZE';

    private const CFG_QOS_PREFETCH_COUNT = 'AMQP_CHANNEL_%s_QOS_PREFETCH_COUNT';

    private const BINDINGS_DEFAULT = '%s:%s:%s';
    private const LIST_QUEUE_POSITION = 0;
    private const LIST_EXCHANGE_POSITION = 1;
    private const LIST_CONNECTION_POSITION = 2;

    public function getName(): string
    {
        return $this->configRoutingKey;
    }

    /**
     * @return BindingConfigurationInterface[]
     */
    public function getBindings(): array
    {
        $bindingsSource = $this->get(self::CFG_BINDINGS, sprintf(
            self::BINDINGS_DEFAULT,
            $this->configRoutingKey,
            $this->configRoutingKey,
            'default'
        ));

        return $this->createBindingsFromSource($bindingsSource);
    }

    /**
     * @return BindingConfigurationInterface[]
     */
    private function createBindingsFromSource(string $bindingsSource): array
    {
        $bindingsArray = array_map('trim', explode(',', $bindingsSource));
        $bindings = [];
        foreach ($bindingsArray as $sourceBinding) {
            $bindings[] = $this->createBindingObject($sourceBinding);
        }

        return $bindings;
    }

    protected function createBindingObject(string $sourceBinding): BindingConfigurationInterface
    {
        $bindingArray = array_map('trim', explode(':', $sourceBinding));

        return new BindingConfiguration(
            $bindingArray[self::LIST_QUEUE_POSITION] ?? $this->configRoutingKey,
            $bindingArray[self::LIST_EXCHANGE_POSITION] ?? $this->configRoutingKey,
            $bindingArray[self::LIST_CONNECTION_POSITION] ?? $this->configRoutingKey,
        );
    }

    public function getQosPrefetchSize(): int
    {
        return (int) $this->get(self::CFG_QOS_PREFETCH_SIZE, 10, false);
    }

    public function getQosPrefetchCount(): int
    {
        return (int) $this->get(self::CFG_QOS_PREFETCH_COUNT, 10, false);
    }

    public function isQosGlobal(): bool
    {
        return (bool) $this->get(self::CFG_QOS_GLOBAL, false, false);
    }
}
