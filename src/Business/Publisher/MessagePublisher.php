<?php

namespace Micro\Plugin\Amqp\Business\Publisher;

use Micro\Plugin\Amqp\Business\Channel\ChannelManagerInterface;
use Micro\Plugin\Amqp\Business\Message\MessageInterface;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

class MessagePublisher implements MessagePublisherInterface
{
    /**
     * @param ChannelManagerInterface                $channelManager
     * @param MessagePublisherConfigurationInterface $publisherConfiguration
     */
    public function __construct(
    private ChannelManagerInterface $channelManager,
    private MessagePublisherConfigurationInterface $publisherConfiguration
    ) {
    }

    /**
     * @param  MessageInterface $message
     * @return void
     */
    public function publish(MessageInterface $message): void
    {
        $channel = $this->getChannel();
        $channel->basic_publish(
            $this->createAmqpMessage($message),
            $this->publisherConfiguration->getExchange()
        );
    }

    /**
     * @return AMQPChannel
     */
    protected function getChannel(): AMQPChannel
    {
        return $this->channelManager->getChannel(
            $this->publisherConfiguration->getConnection(),
            $this->publisherConfiguration->getChannel()
        );
    }

    /**
     * @param  MessageInterface $message
     * @return AMQPMessage
     */
    protected function createAmqpMessage(MessageInterface $message): AMQPMessage
    {
        return new AMQPMessage(
            $message->content(),
            [
                'content_type'  => $this->publisherConfiguration->getContentType(),
                'delivery_mode' => $this->publisherConfiguration->getDeliveryMode(),
            ]
        );
    }
}
