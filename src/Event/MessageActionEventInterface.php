<?php

namespace Micro\Plugin\Amqp\Event;

use Micro\Plugin\Amqp\Business\Message\MessageInterface;

interface MessageActionEventInterface
{
    /**
     * @return MessageInterface
     */
    public function getMessage(): MessageInterface;
}
