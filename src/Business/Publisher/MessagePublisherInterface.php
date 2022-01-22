<?php

namespace Micro\Plugin\Amqp\Business\Publisher;

use Micro\Plugin\Amqp\Business\Message\MessageInterface;

interface MessagePublisherInterface
{
    /**
     * @param  MessageInterface $message
     * @return void
     */
    public function publish(MessageInterface $message): void;
}
