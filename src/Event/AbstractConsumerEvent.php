<?php

namespace Micro\Plugin\Amqp\Event;

abstract class AbstractConsumerEvent implements ConsumerEventInterface
{
    /**
     * @param $consumerName
     */
    public function __construct(private $consumerName)
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
