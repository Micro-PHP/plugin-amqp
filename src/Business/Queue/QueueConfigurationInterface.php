<?php

namespace Micro\Plugin\Amqp\Business\Queue;

interface QueueConfigurationInterface
{
    /**
     * @return bool
     */
    public function isPassive(): bool;

    /**
     * @return bool
     */
    public function isDurable(): bool;

    /**
     * @return bool
     */
    public function isExclusive(): bool;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return bool
     */
    public function isAutoDelete(): bool;

    /**
     * @return string[]
     */
    public function getConnectionList(): array;

    /**
     * @return string[]
     */
    public function getChannelList(): array;
}
