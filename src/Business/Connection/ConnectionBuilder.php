<?php

namespace Micro\Plugin\Amqp\Business\Connection;

use PhpAmqpLib\Connection\AMQPSSLConnection;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class ConnectionBuilder
{
    /**
     * @param  ConnectionConfigurationInterface $configuration
     * @return AMQPStreamConnection
     */
    public function createConnection(ConnectionConfigurationInterface $configuration): AMQPStreamConnection
    {
        if(!$configuration->sslEnabled()) {
            return $this->createBasicConnection($configuration);
        }

        return $this->createSslConnection($configuration);
    }

    /**
     * @param  ConnectionConfigurationInterface $configuration
     * @return AMQPSSLConnection
     */
    private function createSslConnection(ConnectionConfigurationInterface $configuration): AMQPSSLConnection
    {
        $configuration->validateSslConfiguration();

        return new AMQPSSLConnection(
            $configuration->getHost(),
            $configuration->getPort(),
            $configuration->getUsername(),
            $configuration->getPassword(),
            $configuration->getVirtualHost(),
            $this->createSslOptions($configuration),
            $this->createOptions($configuration)
        );
    }

    private function createOptions(ConnectionConfigurationInterface $configuration): array
    {
        return [
            'insist'    => false,
            'login_response'    => null,
            'locale'    => $configuration->getLocale(),
            'connection_timeout' => $configuration->getConnectionTimeout(),
            'read_write_timeout' => $configuration->getReadWriteTimeout(),
            'keepalive' => $configuration->keepAlive(),
            'heartbeat' => 0,
            'channel_rpc_timeout'   => $configuration->getRpcTimeout(),
        ];
    }

    /**
     * @param  ConnectionConfigurationInterface $configuration
     * @return array
     */
    private function createSslOptions(ConnectionConfigurationInterface $configuration): array
    {
        return [
            'cafile' => $configuration->getCaCert(),
            'local_pk'  => $configuration->getCert(),
            'verify_peer' => $configuration->isVerify(),
        ];
    }

    /**
     * @param  ConnectionConfigurationInterface $configuration
     * @return AMQPStreamConnection
     */
    private function createBasicConnection(ConnectionConfigurationInterface $configuration): AMQPStreamConnection
    {
        return new AMQPStreamConnection(
            $configuration->getHost(),
            $configuration->getPort(),
            $configuration->getUsername(),
            $configuration->getPassword(),
            $configuration->getVirtualHost(),
            false,
            $configuration->getSaslMethod(),
            null,
            $configuration->getLocale(),
            $configuration->getConnectionTimeout(),
            $configuration->getReadWriteTimeout(),
            null,
            $configuration->keepAlive(),
            0,
            $configuration->getRpcTimeout(),
            null
        );
    }
}
