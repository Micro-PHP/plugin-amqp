<?php

namespace Micro\Plugin\Amqp\Event;

use Micro\Component\EventEmitter\EventInterface;
use Micro\Plugin\Amqp\Business\Message\MessageReceivedInterface;

interface NackMessageEventInterface extends EventInterface
{
    /**
     * @return MessageReceivedInterface
     */
    public function getMessage(): MessageReceivedInterface;
}
