<?php

namespace Micro\Plugin\Amqp\Business\Channel;

use Micro\Plugin\Amqp\AbstractAmqpComponentConfiguration;
use Micro\Plugin\Amqp\AmqpPluginConfiguration;

class ChannelConfiguration extends AbstractAmqpComponentConfiguration implements ChannelConfigurationInterface
{
    private const CFG_BINDINGS             = 'AMQP_CHANNEL_%s_BINDINGS';
    private const BINDINGS_DEFAULT         =    AmqpPluginConfiguration::QUEUE_DEFAULT . ':' .
                                        AmqpPluginConfiguration::EXCHANGE_DEFAULT . ':' .
                                        AmqpPluginConfiguration::CONNECTION_DEFAULT;
    private const LIST_QUEUE_POSITION      = 0;
    private const LIST_EXCHANGE_POSITION   = 1;
    private const LIST_CONNECTION_POSITION = 2;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->configRoutingKey;
    }

    /**
     * @return BindingConfigurationInterface[]
     */
    public function getBindings(): array
    {
        $bindingsSource = $this->get(self::CFG_BINDINGS, self::BINDINGS_DEFAULT);

        return $this->createBindingsFromSource($bindingsSource);
    }

    /**
     * @param  string $bindingsSource
     * @return BindingConfigurationInterface[]
     */
    private function createBindingsFromSource(string $bindingsSource): array
    {
        $bindingsArray = array_map('trim', explode(',', $bindingsSource));
        $bindings      = [];
        foreach ($bindingsArray as $sourceBinding) {
            $bindings[] = $this->createBindingObject($sourceBinding);
        }

        return $bindings;
    }

    /**
     * @param  string $sourceBinding
     * @return BindingConfigurationInterface
     */
    protected function createBindingObject(string $sourceBinding): BindingConfigurationInterface
    {
        $bindingArray = array_map('trim', explode(':', $sourceBinding));

        return new BindingConfiguration(
            $bindingArray[self::LIST_QUEUE_POSITION] ?? AmqpPluginConfiguration::QUEUE_DEFAULT,
            $bindingArray[self::LIST_EXCHANGE_POSITION] ?? AmqpPluginConfiguration::EXCHANGE_DEFAULT,
            $bindingArray[self::LIST_CONNECTION_POSITION] ?? AmqpPluginConfiguration::CONNECTION_DEFAULT,
        );
    }
}
