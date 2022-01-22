<?php

namespace Micro\Plugin\Amqp\Business\Connection;

use PhpAmqpLib\Connection\AMQPStreamConnection;

interface ConnectionManagerInterface
{
    /**
     * @param  string $connectionName
     * @return AMQPStreamConnection
     */
    public function getConnection(string $connectionName): AMQPStreamConnection;

    /**
     * @param  string $connectionName
     * @return void
     */
    public function closeConnection(string $connectionName): void;

    /**
     * @return void
     */
    public function closeConnectionsAll(): void;
}
