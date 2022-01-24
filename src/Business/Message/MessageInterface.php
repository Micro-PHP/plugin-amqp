<?php

namespace Micro\Plugin\Amqp\Business\Message;

interface MessageInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return string
     */
    public function getContent(): string;
}
