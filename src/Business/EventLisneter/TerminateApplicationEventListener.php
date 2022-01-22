<?php

namespace Micro\Plugin\Amqp\Business\EventLisneter;

use Micro\Component\EventEmitter\EventInterface;
use Micro\Component\EventEmitter\EventListenerInterface;
use Micro\Kernel\App\Business\Event\ApplicationTerminatedEvent;
use Micro\Plugin\Amqp\Business\Connection\ConnectionManagerInterface;

class TerminateApplicationEventListener implements EventListenerInterface
{
    /**
     * @param ConnectionManagerInterface|null $connectionManager
     */
    public function __construct(private ?ConnectionManagerInterface $connectionManager)
    {
    }

    /**
     * @param  ApplicationTerminatedEvent $event
     * @return void
     */
    public function on(EventInterface $event): void
    {
        if(!$this->connectionManager) {
            return;
        }

        $this->connectionManager->closeConnectionsAll();
    }

    /**
     * {@inheritDoc}
     */
    public function supports(EventInterface $event): bool
    {
        return $event instanceof ApplicationTerminatedEvent;
    }
}
