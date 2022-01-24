<?php

namespace Micro\Plugin\Amqp\Business\Consumer;



use Micro\Plugin\Amqp\Business\Message\MessageReceivedInterface;

interface ConsumerProcessorInterface
{
    /**
     * @param MessageReceivedInterface $message
     * @return bool
     */
    public function receive(MessageReceivedInterface $message): bool;

    /**
     * @return string
     */
    public function name(): string;
}
