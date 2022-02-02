<?php

namespace Micro\Plugin\Amqp\Event;

abstract class AbstractConsumerEvent implements ConsumerEventInterface
{
    /**
     * @param string $consumerName
     */
    public function __construct(private string $consumerName)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function getConsumerName(): string
    {
        return $this->consumerName;
    }
}
