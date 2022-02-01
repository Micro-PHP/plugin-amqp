<?php

namespace Micro\Plugin\Amqp\Event;

use Micro\Component\EventEmitter\EventInterface;
use Micro\Plugin\Amqp\Business\Message\MessageInterface;

class AbstractActionMessageEvent implements EventInterface, MessageActionEventInterface
{
    /**
     * @param MessageInterface $message
     */
    public function __construct(private MessageInterface $message)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function getMessage(): MessageInterface
    {
        return $this->message;
    }
}
