<?php

namespace Micro\Plugin\Amqp\Business\Queue;

use Micro\Plugin\Amqp\AmqpPluginConfiguration;
use Micro\Plugin\Amqp\Business\Channel\ChannelManagerInterface;

class QueueManager implements QueueManagerInterface
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
    public function queueDeclare(string $connectionName, string $queueName, string $channelName = null): void
    {
        $queueConfiguration = $this->pluginConfiguration->getQueueConfiguration($queueName);
        $channel            = $this->channelManager->getChannel($channelName, $connectionName);
        $channel->queue_declare(
            $queueConfiguration->getName(),
            $queueConfiguration->isPassive(),
            $queueConfiguration->isDurable(),
            $queueConfiguration->isExclusive(),
            $queueConfiguration->isAutoDelete()
        );
    }

    /**
     * @TODO:
     *
     * @return void
     */
    public function configure(): void
    {
        $queueList = $this->pluginConfiguration->getQueueList();

        foreach ($queueList as $queueName) {
            $queueConfiguration = $this->pluginConfiguration->getQueueConfiguration($queueName);
            $connectionList     = $queueConfiguration->getConnectionList();

            foreach ($connectionList as $connectionName) {
                $channelList = $queueConfiguration->getChannelList();

                foreach ($channelList as $channelName) {
                    $this->queueDeclare($connectionName, $queueConfiguration->getName(), $channelName);
                }
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function bindings(): void
    {
        $channels = $this->pluginConfiguration->getChannelList();

        foreach ($channels as $channelName) {
            $this->channelBind($channelName);
        }
    }

    /**
     * @param  string $channelName
     * @return void
     */
    protected function channelBind(string $channelName): void
    {
        $configuration = $this->pluginConfiguration->getChannelConfiguration($channelName);
        $bindings      = $configuration->getBindings();

        foreach ($bindings as $binding) {
            $channel = $this->channelManager->getChannel(
                $channelName,
                $binding->getConnection()
            );

            $channel->queue_bind(
                $binding->getQueueName(),
                $binding->getExchangeName()
            );
        }
    }
}
