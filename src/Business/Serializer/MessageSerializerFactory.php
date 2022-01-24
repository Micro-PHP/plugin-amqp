<?php

namespace Micro\Plugin\Amqp\Business\Serializer;

use Micro\Plugin\Serializer\SerializerFacadeInterface;

class MessageSerializerFactory implements MessageSerializerFactoryInterface
{
    /**
     * @param SerializerFacadeInterface $serializerFacade
     */
    public function __construct(private SerializerFacadeInterface $serializerFacade) {}

    /**
     * {@inheritDoc}
     */
    public function create(): MessageSerializerInterface
    {
        return new MessageSerializer($this->serializerFacade);
    }
}
