<?php

namespace Micro\Plugin\Amqp\Business\Message;

interface MessageInterface
{
    /**
     * @return string
     */
    public function id(): string;

    /**
     * @return string
     */
    public function content(): string;
}
