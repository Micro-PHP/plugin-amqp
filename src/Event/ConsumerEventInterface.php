<?php

namespace Micro\Plugin\Amqp\Event;

use Micro\Component\EventEmitter\EventInterface;

interface ConsumerEventInterface extends EventInterface
{
    /**
     * @return string
     */
    public function getConsumerName(): string;
}
