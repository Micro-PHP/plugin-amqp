<?php

namespace Micro\Plugin\Amqp\Business\Queue;

interface QueueManagerInterface
{
    /**
     * @param  string      $connectionName
     * @param  string      $queueName
     * @param  string|null $channelName
     * @return void
     */
    public function queueDeclare(string $connectionName, string $queueName, string $channelName = null): void;
}
