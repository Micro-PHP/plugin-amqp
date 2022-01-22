<?php

namespace Micro\Plugin\Amqp\Business\Exchange;

interface ExchangeManagerInterface
{
    /**
     * @param  string      $connectionName
     * @param  string      $exchangeName
     * @param  string|null $channelName
     * @return void
     */
    public function exchangeDeclare(string $connectionName, string $exchangeName, string $channelName = null): void;

    /**
     * @return void
     */
    public function configure(): void;
}
