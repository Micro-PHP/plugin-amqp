<?php

namespace Micro\Plugin\Amqp\Business\Message;

use Micro\Plugin\Amqp\Event\AckMessageEvent;
use Micro\Plugin\Amqp\Event\NackMessageEvent;
use Micro\Plugin\EventEmitter\EventsFacadeInterface;
use PhpAmqpLib\Message\AMQPMessage;

class MessageReceived implements MessageReceivedInterface
{
    /**
     * @param AMQPMessage $source
     * @param MessageInterface $message
     * @param EventsFacadeInterface $eventsFacade
     */
    public function __construct(
    private AMQPMessage $source,
    private MessageInterface $message,
    private EventsFacadeInterface $eventsFacade
    )
    {
    }

    /**
     * {@inheritDoc}
     */
    protected function source(): AMQPMessage
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

        $this->eventsFacade->emit(new AckMessageEvent($this));
    }

    /**
     * {@inheritDoc}
     */
    public function nack(bool $requeue = false, bool $multiple = false): void
    {
        $this->source()->nack($requeue, $multiple);

        $this->eventsFacade->emit(new NackMessageEvent($this));
    }
}
