<?php

namespace Micro\Plugin\Amqp\Business\Exchange;

use Micro\Plugin\Amqp\AmqpPluginConfiguration;
use Micro\Plugin\Amqp\Business\Channel\ChannelManagerInterface;
use Psr\Log\LoggerInterface;

class ExchangeManager implements ExchangeManagerInterface
{
    /**
     * @param ChannelManagerInterface $channelManager
     * @param AmqpPluginConfiguration $pluginConfiguration
     */
    public function __construct(
    private ChannelManagerInterface $channelManager,
    private AmqpPluginConfiguration $pluginConfiguration,
    private LoggerInterface $logger
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function exchangeDeclare(string $connectionName, string $exchangeName, string $channelName = null): void
    {
        $channel = $this->channelManager->getChannel($connectionName, $channelName);
        $configuration = $this->pluginConfiguration->getExchangeConfiguration($exchangeName);

        $channel->exchange_declare(
            $exchangeName,
            $configuration->getType(),
            $configuration->isPassive(),
            $configuration->isDurable(),
            $configuration->isAutoDelete(),
            $configuration->isInternal(),
            $configuration->isNoWait(),
            $configuration->getArguments(),
            $configuration->getTicket()
        );

        $this->debug($configuration, $connectionName, $exchangeName, $channelName);
    }

    /**
     * @TODO:
     *
     * {@inheritDoc}
     */
    public function configure(): void
    {
        $exchangeList = $this->pluginConfiguration->getExchangeList();
        foreach ($exchangeList as $exchangeName) {
            $exchangeConfig = $this->pluginConfiguration->getExchangeConfiguration($exchangeName);
            $connectionList = $exchangeConfig->getConnectionList();
            foreach ($connectionList as $connectionName) {
                foreach ($exchangeConfig->getChannelList() as $channelName) {
                    $this->exchangeDeclare($connectionName, $exchangeName, $channelName);
                }
            }
        }
    }

    /**
     * @param  ExchangeConfiguration $configuration
     * @param  string                $connectionName
     * @param  string                $exchangeName
     * @param  string|null           $channelName
     * @return void
     */
    private function debug(
        ExchangeConfiguration $configuration,
        string $connectionName,
        string $exchangeName,
        string $channelName = null
    ): void {
        $this->logger->debug(
            'AMQP: Exchange declared', [
            'connection'  => $connectionName,
            'exchange'  => $exchangeName,
            'channel'   => $channelName,
            'declare_options' => [
                'type'  => $configuration->getType(),
                'passive'   => $configuration->isPassive(),
                'durable' => $configuration->isDurable(),
                'auto_delete' => $configuration->isAutoDelete(),
                'internal' => $configuration->isInternal(),
                'no_wait' => $configuration->isNoWait(),
                'arguments' => $configuration->getArguments(),
                'ticket' => $configuration->getTicket(),
            ]
            ]
        );
    }
}
