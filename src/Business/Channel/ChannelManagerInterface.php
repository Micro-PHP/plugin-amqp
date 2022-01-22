<?php

namespace Micro\Plugin\Amqp\Business\Channel;

use PhpAmqpLib\Channel\AMQPChannel;

interface ChannelManagerInterface
{
    /**
     * @param  string      $channelName
     * @param  string|null $connectionName
     * @return AMQPChannel
     */
    public function getChannel(string $channelName, string $connectionName = null): AMQPChannel;
}
