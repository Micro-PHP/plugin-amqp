<?php

namespace Micro\Plugin\Amqp\Business\Message;


use PhpAmqpLib\Message\AMQPMessage;

class MessageReceived implements MessageReceivedInterface
{
    /**
     * @param AMQPMessage $source
     */
    public function __construct(private AMQPMessage $source, private MessageInterface $message)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function source(): AMQPMessage
    {
        return $this->source;
    }

    /**
     * {@inheritDoc}
     */
    public function content(): MessageInterface
    {
        return $this->message;
    }

    /**
     * {@inheritDoc}
     */
    public function ack(bool $multiple = false): void
    {
        $this->source()->ack();
    }

    /**
     * {@inheritDoc}
     */
    public function nack(bool $requeue = false, bool $multiple = false): void
    {
        $this->source()->nack($requeue, $multiple);
    }
}
