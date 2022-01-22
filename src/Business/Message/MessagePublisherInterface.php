<?php

namespace Micro\Plugin\Amqp\Business\Message;

interface MessagePublisherInterface
{
    /**
     * @param  MessageInterface $message
     * @return void
     */
    public function publish(MessageInterface $message): void;
}
