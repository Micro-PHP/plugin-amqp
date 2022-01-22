<?php

namespace Micro\Plugin\Amqp\Business\Publisher;

use Micro\Plugin\Amqp\AmqpPluginConfiguration;
use Micro\Plugin\Amqp\Business\Channel\ChannelManagerInterface;

class MessagePublisherManager implements MessagePublisherManagerInterface
{
    /**
     * @var array
     */
    private array $publisherCollection;

    /**
     * @param ChannelManagerInterface $channelManager
     * @param AmqpPluginConfiguration $pluginConfiguration
     */
    public function __construct(
    private ChannelManagerInterface $channelManager,
    private AmqpPluginConfiguration $pluginConfiguration
    ) {
        $this->publisherCollection = [];
    }

    /**
     * {@inheritDoc}
     */
    public function getPublisher(string $publisherName): MessagePublisherInterface
    {
        if(!empty($this->publisherCollection[$publisherName])) {
            return $this->publisherCollection[$publisherName];
        }

        return $this->publisherCollection[$publisherName] = $this->createMessagePublisher($publisherName);
    }

    /**
     * @param  string $publisherName
     * @return MessagePublisherInterface
     */
    protected function createMessagePublisher(string $publisherName): MessagePublisherInterface
    {
        return new MessagePublisher(
            $this->channelManager,
            $this->pluginConfiguration->getPublisherConfiguration($publisherName)
        );
    }
}
