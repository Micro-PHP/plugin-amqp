<?php

namespace Micro\Plugin\Amqp\Business\EventListener;

use Micro\Component\EventEmitter\EventInterface;
use Micro\Component\EventEmitter\EventListenerInterface;
use Micro\Kernel\App\Business\Event\ApplicationReadyEvent;

class StartApplicationEventListener implements EventListenerInterface
{
    public function __construct()
    {

    }

    /**
     * @param ApplicationReadyEvent $event
     * @return void
     */
    public function on(EventInterface $event): void
    {

    }

    public function supports(EventInterface $event): bool
    {
        return $event instanceof ApplicationReadyEvent;
    }
}
