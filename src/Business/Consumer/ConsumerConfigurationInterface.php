<?php

namespace Micro\Plugin\Amqp\Business\Consumer;

interface ConsumerConfigurationInterface
{
    /**
     * @return bool
     */
    public function isNoWait(): bool;

    /**
     * @return bool
     */
    public function isExclusive(): bool;

    /**
     * @return bool
     */
    public function isNoAck(): bool;

    /**
     * @return bool
     */
    public function isNoLocal(): bool;
    /**
     * @return string
     */
    public function getQueue(): string;

    /**
     * @return string
     */
    public function getChannel(): string;

    /**
     * @return string
     */
    public function getConnection(): string;

    /**
     * @return string
     */
    public function getTag(): string;
}
