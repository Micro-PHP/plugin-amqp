<?php

namespace Micro\Plugin\Amqp\Event;

use Micro\Component\EventEmitter\EventInterface;
use Micro\Plugin\Amqp\Business\Message\MessageReceivedInterface;

interface AckMessageEventInterface extends EventInterface
{
    /**
     * @return MessageReceivedInterface
     */
    public function getMessage(): MessageReceivedInterface;
}
