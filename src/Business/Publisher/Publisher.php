<?php

namespace Micro\Plugin\Amqp\Business\Publisher;

use Micro\Plugin\Amqp\Business\Channel\ChannelManagerInterface;
use Micro\Plugin\Amqp\Business\Message\MessageInterface;
use Micro\Plugin\Amqp\Business\Serializer\MessageSerializerFactoryInterface;
use Micro\Plugin\Amqp\Event\PublishMessageEvent;
use Micro\Plugin\EventEmitter\EventsFacadeInterface;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

class Publisher implements PublisherInterface
{
    /**
     * @param ChannelManagerInterface $channelManager
     * @param PublisherConfigurationInterface $publisherConfiguration
     * @param MessageSerializerFactoryInterface $messageSerializerFactory
     * @param EventsFacadeInterface $eventsFacade
     */
    public function __construct(
    private ChannelManagerInterface                 $channelManager,
    private PublisherConfigurationInterface         $publisherConfiguration,
    private MessageSerializerFactoryInterface       $messageSerializerFactory,
    private EventsFacadeInterface                   $eventsFacade
    )
    {
    }

    /**
     * @param  MessageInterface $message
     * @return void
     */
    public function publish(MessageInterface $message): void
    {
        $channel = $this->getChannel();
        $this->eventsFacade->emit(new PublishMessageEvent($message, $this->publisherConfiguration));
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
            $this->publisherConfiguration->getChannel(),
            $this->publisherConfiguration->getConnection()
        );
    }

    /**
     * @param  MessageInterface $message
     * @return AMQPMessage
     */
    protected function createAmqpMessage(MessageInterface $message): AMQPMessage
    {
        return new AMQPMessage(
            $this->createContent($message),
            [
                'content_type'  => $this->publisherConfiguration->getContentType(),
                'delivery_mode' => $this->publisherConfiguration->getDeliveryMode(),
            ]
        );
    }

    /**
     * @param MessageInterface $message
     * @return string
     */
    protected function createContent(MessageInterface $message): string
    {
        return $this->messageSerializerFactory->create()->serialize($message);
    }
}
