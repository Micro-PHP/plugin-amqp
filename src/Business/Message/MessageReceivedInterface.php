<?php

namespace Micro\Plugin\Amqp\Business\Message;

use PhpAmqpLib\Message\AMQPMessage;

interface MessageReceivedInterface
{
    /**
     * @return MessageInterface
     */
    public function content(): MessageInterface;

    /**
     * @param bool $multiple
     * @return void
     */
    public function ack(bool $multiple = false): void;

    /**
     * @param bool $requeue
     * @param bool $multiple
     * @return void
     */
    public function nack(bool $requeue = false, bool $multiple = false): void;
}
