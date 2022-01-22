<?php

namespace Micro\Plugin\Amqp\Business\Consumer;

interface ConsumerConfigurationInterface
{
    /**
     * @return string[]
     */
    public function getChannels(): array;

    /**
     * @return string[]
     */
    public function getConnections(): array;

    /**
     * @return string
     */
    public function getTag(): string;
}
