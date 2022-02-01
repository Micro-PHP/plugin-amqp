<?php

namespace Micro\Plugin\Amqp\Event;

use Micro\Plugin\Amqp\Business\Message\MessageReceivedInterface;

class MessageReceivedEvent implements MessageReceivedEventInterface
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
