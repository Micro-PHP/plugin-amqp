<?php

namespace Micro\Plugin\Amqp\Business\Publisher;

use Micro\Plugin\Amqp\AmqpPluginConfiguration;
use Micro\Plugin\Amqp\Business\Channel\ChannelManagerInterface;

class PublisherManagerFactory implements PublisherManagerFactoryInterface
{

    public function __construct(
        private ChannelManagerInterface $channelManager,
        private AmqpPluginConfiguration $pluginConfiguration
    ) {}

    public function create(): PublisherManagerInterface
    {
        // TODO: Implement create() method.
    }
}
