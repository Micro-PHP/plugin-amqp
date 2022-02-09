<?php

namespace Micro\Plugin\Amqp\Event;

use Micro\Component\EventEmitter\EventInterface;
use Micro\Plugin\Amqp\Business\Message\MessageInterface;

interface MessageActionEventInterface extends EventInterface
{
    /**
     * @return MessageInterface
     */
    public function getMessage(): MessageInterface;
}
