<?php

namespace Micro\Plugin\Amqp\Business\Exchange;

interface ExchangeConfigurationInterface
{
    /**
     * @return string
     */
    public function getType(): string;

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
    public function isAutoDelete(): bool;

    /**
     * @return bool
     */
    public function isInternal(): bool;

    /**
     * @return bool
     */
    public function isNoWait(): bool;

    /**
     * @return array
     */
    public function getArguments(): array;

    /**
     * @return int|null
     */
    public function getTicket(): ?int;

    /**
     * @return string[]
     */
    public function getConnectionList(): array;

    /**
     * @return string[]
     */
    public function getChannelList(): array;
}
