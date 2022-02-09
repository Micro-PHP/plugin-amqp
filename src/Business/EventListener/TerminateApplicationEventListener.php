<?php

namespace Micro\Plugin\Amqp\Business\EventListener;

use Micro\Component\EventEmitter\EventInterface;
use Micro\Component\EventEmitter\EventListenerInterface;
use Micro\Kernel\App\Business\Event\ApplicationTerminatedEvent;
use Micro\Plugin\Amqp\AmqpFacadeInterface;

class TerminateApplicationEventListener implements EventListenerInterface
{
    /**
     * @param AmqpFacadeInterface $amqpFacade
     */
    public function __construct(private AmqpFacadeInterface $amqpFacade)
    {
    }

    /**
     * @param  ApplicationTerminatedEvent $event
     * @return void
     */
    public function on(EventInterface $event): void
    {
        $this->amqpFacade->terminate();
    }

    /**
     * {@inheritDoc}
     */
    public function supports(EventInterface $event): bool
    {
        return $event instanceof ApplicationTerminatedEvent;
    }
}
