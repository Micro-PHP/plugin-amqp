<?php

namespace Micro\Plugin\Amqp\Business\Channel;

use Micro\Plugin\Amqp\Business\Connection\ConnectionManagerInterface;
use PhpAmqpLib\Channel\AMQPChannel;


class ChannelManager implements ChannelManagerInterface
{
    /**
     * @var array<string, int>
     */
    private array $channels;
    /**
     * @param ConnectionManagerInterface $connectionManager
     */
    public function __construct(
        private ConnectionManagerInterface $connectionManager
    ) {
        $this->channels = [];
    }

    /**
     * {@inheritDoc}
     */
    public function getChannel(string $channelName, string $connectionName = null): AMQPChannel
    {
        $channelId = null;
        if(!empty($this->channels[$channelName])) {
            $channelId = $this->channels[$channelName];
        }

        $channel =  $this->connectionManager
            ->getConnection($connectionName)
            ->channel($channelId);

        $this->channels[$channelName] = $channel->getChannelId();

        return $channel;
    }

    public function init(): void
    {
    }
}
