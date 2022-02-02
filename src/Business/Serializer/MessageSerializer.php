<?php

namespace Micro\Plugin\Amqp\Business\Serializer;

use Micro\Plugin\Amqp\Business\Message\MessageInterface;
use Micro\Plugin\Serializer\Business\MessageSerialized;
use Micro\Plugin\Serializer\SerializerFacadeInterface;

class MessageSerializer implements MessageSerializerInterface
{
    private const FORMAT = SerializerFacadeInterface::FORMAT_JSON;

    /**
     * @param SerializerFacadeInterface $serializerFacade
     */
    public function __construct(private SerializerFacadeInterface $serializerFacade)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function serialize(MessageInterface $message): string
    {
        return $this->serializerFacade->serialize($this->createSerializedMessage($message),self::FORMAT);
    }

    /**
     * {@inheritDoc}
     */
    public function deserialize(string $content): MessageInterface
    {
        /**
         * @var MessageSerialized $messageSerializedObject
         */
        $messageSerializedObject = $this->serializerFacade->deserialize($content, MessageSerialized::class, self::FORMAT);

        return $this->serializerFacade->deserialize(
            $messageSerializedObject->getSource(),
            $messageSerializedObject->getClassName(),
            self::FORMAT
        );
    }

    /**
     * @param MessageInterface $message
     *
     * @return string
     */
    protected function serializeMessage(MessageInterface $message): string
    {
        return $this->serializerFacade->serialize($message, self::FORMAT);
    }

    /**
     * @param MessageInterface $message
     * @return MessageSerialized
     */
    protected function createSerializedMessage(MessageInterface $message): MessageSerialized
    {
        return new MessageSerialized(get_class($message), $this->serializeMessage($message));
    }
}
