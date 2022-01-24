<?php

namespace Micro\Plugin\Amqp\Business\Consumer;


use Micro\Plugin\Amqp\Business\Message\MessageInterface;
use Micro\Plugin\Amqp\Business\Message\MessageReceived;
use Micro\Plugin\Amqp\Business\Message\MessageReceivedInterface;
use Micro\Plugin\Amqp\Business\Serializer\MessageSerializerFactoryInterface;
use PhpAmqpLib\Message\AMQPMessage;

class ConsumerProcessorProxyBuilder
{
    /**
     * @param MessageSerializerFactoryInterface $messageSerializerFactory
     */
    public function __construct(private MessageSerializerFactoryInterface $messageSerializerFactory)
    {
    }

    /**
     * @param ConsumerProcessorInterface $processor
     * @return \Closure
     */
    public function createProxy(ConsumerProcessorInterface $processor): \Closure
    {
        return function(AMQPMessage $message) use ($processor) {
            $processor->receive($this->createMessage($message));
        };
    }

    /**
     * @param AMQPMessage $amqpMessage
     * @return MessageReceivedInterface
     */
    protected function createMessage(AMQPMessage $amqpMessage): MessageReceivedInterface
    {
        return new MessageReceived($amqpMessage, $this->deserializeMessageContent($amqpMessage));
    }

    /**
     * @param AMQPMessage $amqpMessage
     * @return MessageInterface
     */
    protected function deserializeMessageContent(AMQPMessage $amqpMessage): MessageInterface
    {
        return $this->messageSerializerFactory->create()->deserialize($amqpMessage->getBody());
    }
}
