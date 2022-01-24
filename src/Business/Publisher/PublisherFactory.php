<?php

namespace Micro\Plugin\Amqp\Business\Publisher;

use Micro\Plugin\Amqp\AmqpPluginConfiguration;
use Micro\Plugin\Amqp\Business\Channel\ChannelManagerInterface;
use Micro\Plugin\Amqp\Business\Serializer\MessageSerializerFactoryInterface;


class PublisherFactory implements PublisherFactoryInterface
{
    /**
     * @param ChannelManagerInterface $channelManager
     * @param AmqpPluginConfiguration $pluginConfiguration
     * @param MessageSerializerFactoryInterface $messageSerializerFactory
     */
    public function __construct(
        private ChannelManagerInterface $channelManager,
        private AmqpPluginConfiguration $pluginConfiguration,
        private MessageSerializerFactoryInterface $messageSerializerFactory

    ) {}

    /**
     * {@inheritDoc}
     */
    public function create(string $publisherName): PublisherInterface
    {
        return new Publisher(
            $this->channelManager,
            $this->pluginConfiguration->getPublisherConfiguration($publisherName),
            $this->messageSerializerFactory
        );
    }
}
