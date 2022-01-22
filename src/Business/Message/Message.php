<?php

namespace Micro\Plugin\Amqp\Business\Message;

class Message implements MessageInterface
{
    /**
     * @param string $id
     * @param string $content
     */
    public function __construct(private string $id, private string $content)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function content(): string
    {
        return $this->content;
    }

    /**
     * {@inheritDoc}
     */
    public function id(): string
    {
        return (string)$this->id;
    }
}
