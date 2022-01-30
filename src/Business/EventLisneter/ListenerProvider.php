<?php

namespace Micro\Plugin\Amqp\Business\EventLisneter;

use Micro\Component\EventEmitter\EventListenerInterface;
use Micro\Component\EventEmitter\Impl\Provider\AbstractListenerProvider;
use Micro\Plugin\Amqp\AmqpPlugin;

class ListenerProvider extends AbstractListenerProvider
{
    /**
     * @var iterable|EventListenerInterface[]
     */
    private iterable $eventListenerCollection;

    /**
     * @param EventListenerInterface ...$eventListenerCollection
     */
    public function __construct(EventListenerInterface ...$eventListenerCollection)
    {
        $this->eventListenerCollection = $eventListenerCollection;
    }

    /**
     * {@inheritDoc}
     */
    public function getEventListeners(): iterable
    {
        return $this->eventListenerCollection;
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return 'amqp';
    }
}
