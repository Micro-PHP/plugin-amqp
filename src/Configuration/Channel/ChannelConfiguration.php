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
}
