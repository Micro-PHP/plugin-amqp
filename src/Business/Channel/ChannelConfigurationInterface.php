<?php

namespace Micro\Plugin\Amqp\Business\Channel;

interface ChannelConfigurationInterface
{
    /**
     * @return BindingConfigurationInterface[]
     */
    public function getBindings(): array;
}
