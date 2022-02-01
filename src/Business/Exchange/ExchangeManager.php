<?php

namespace Micro\Plugin\Amqp\Business\Exchange;

use Micro\Plugin\Amqp\AmqpPluginConfiguration;
use Micro\Plugin\Amqp\Business\Channel\ChannelManagerInterface;

class ExchangeManager implements ExchangeManagerInterface
{
    /**
     * @param ChannelManagerInterface $channelManager
     * @param AmqpPluginConfiguration $pluginConfiguration
     */
    public function __construct(
        private ChannelManagerInterface $channelManager,
        private AmqpPluginConfiguration $pluginConfiguration
    )
    {
    }

    /**
     * {@inheritDoc}
     */
    public function exchangeDeclare(string $connectionName, string $exchangeName, string $channelName = null): void
    {
        $channel = $this->channelManager->getChannel($channelName, $connectionName);
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
}
