<?php

namespace Micro\Plugin\Amqp;

use Micro\Plugin\Amqp\Business\Consumer\ConsumerInterface;
use Micro\Plugin\Amqp\Business\Message\MessageInterface;

interface AmqpFacadeInterface
{
    /**
     * @param  ConsumerInterface $consumer
     * @return void
     */
    public function consume(ConsumerInterface $consumer): void;

    /**
     * @param  MessageInterface $message
     * @return void
     */
    public function publish(MessageInterface $message): void;
}
