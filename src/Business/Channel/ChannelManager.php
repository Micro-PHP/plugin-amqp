<?php

/*
 *  This file is part of the Micro framework package.
 *
 *  (c) Stanislau Komar <kost@micro-php.net>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Micro\Plugin\Amqp\Business\Channel;

use Micro\Plugin\Amqp\AmqpPluginConfiguration;
use Micro\Plugin\Amqp\Business\Connection\ConnectionManagerInterface;
use PhpAmqpLib\Channel\AMQPChannel;

readonly class ChannelManager implements ChannelManagerInterface
{
    public function __construct(
        private ConnectionManagerInterface $connectionManager,
        private AmqpPluginConfiguration $pluginConfiguration
    ) {
    }

    public function getChannel(string $connectionName): AMQPChannel
    {
        $channel = $this->connectionManager
            ->getConnection($connectionName)
            ->channel();

        $channelCfg = $this->pluginConfiguration->getChannelConfiguration($connectionName);

        $prefetchCount = $channelCfg->getQosPrefetchCount();
        if ($prefetchCount) {
            $channel->basic_qos(
                0,
                $prefetchCount,
                false,
            );
        }

        return $channel;
    }
}
