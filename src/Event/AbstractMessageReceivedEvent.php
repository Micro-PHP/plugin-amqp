<?php

namespace Micro\Plugin\Amqp\Event;

use Micro\Plugin\Amqp\Business\Message\MessageReceivedInterface;

abstract class AbstractMessageReceivedEvent implements MessageReceivedEventInterface
{
    /**
     * @param MessageReceivedInterface $messageReceived
     */
    public function __construct(
        private MessageReceivedInterface $messageReceived
    )
    {
    }

    /**
     * {@inheritDoc}
     */
    public function getMessage(): MessageReceivedInterface
    {
        return $this->messageReceived;
    }
}
