<?php

namespace Micro\Plugin\Amqp\Business\Consumer;


use Micro\Plugin\Amqp\Business\Message\MessageInterface;

interface ConsumerInterface
{
    /**
     * @param  MessageInterface $message
     * @return boolean
     */
    public function receive(MessageInterface $message): bool;

    /**
     * @return string
     */
    public function name(): string;
}
