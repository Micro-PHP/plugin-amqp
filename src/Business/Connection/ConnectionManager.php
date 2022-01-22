<?php

namespace Micro\Plugin\Amqp\Business\Connection;


use Micro\Plugin\Amqp\AmqpPluginConfiguration;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Psr\Log\LoggerInterface;

class ConnectionManager implements ConnectionManagerInterface
{
    /**
     * @var array<string, AMQPStreamConnection>
     */
    private array $connections;

    public function __construct(
    private AmqpPluginConfiguration $configuration,
    private ConnectionBuilder $connectionBuilder,
    private LoggerInterface $logger
    ) {
        $this->connections = [];
    }

    /**
     * {@inheritDoc}
     */
    public function getConnection(string $connectionName): AMQPStreamConnection
    {
        if(array_key_exists($connectionName, $this->connections)) {
            return $this->connections[$connectionName];
        }

        return $this->connections[$connectionName] = $this->createConnection($connectionName);

    }

    /**
     * @param  string $connectionName
     * @return AMQPStreamConnection
     */
    protected function createConnection(string $connectionName): AMQPStreamConnection
    {
        $this->logger->debug(sprintf('AMQP: Create connection "%s"', $connectionName));

        return $this->connectionBuilder->createConnection(
            $this->configuration->getConnectionConfiguration($connectionName)
        );
    }

    /**
     * @param  string $connectionName
     * @return void
     * @throws \Exception
     */
    public function closeConnection(string $connectionName): void
    {
        $connection = $this->connections[$connectionName];
        $this->logger->debug(sprintf('AMQP: Close connection "%s"', $connectionName));
        $connection->close();
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function closeConnectionsAll(): void
    {
        foreach (array_keys($this->connections) as $connectionName) {
            $this->closeConnection($connectionName);
        }
    }
}
