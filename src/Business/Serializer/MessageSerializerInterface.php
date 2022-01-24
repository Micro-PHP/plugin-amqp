<?php

namespace Micro\Plugin\Amqp\Business\Serializer;

use Micro\Plugin\Amqp\Business\Message\MessageInterface;

interface MessageSerializerInterface
{
    /**
     * @param MessageInterface $message
     * @return string
     */
    public function serialize(MessageInterface $message): string;

    /**
     * @param string $content
     * @return MessageInterface
     */
    public function deserialize(string $content): MessageInterface;
}
