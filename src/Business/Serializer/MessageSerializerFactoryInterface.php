<?php

namespace Micro\Plugin\Amqp\Business\Serializer;

interface MessageSerializerFactoryInterface
{
    /**
     * @return MessageSerializerInterface
     */
    public function create(): MessageSerializerInterface;
}
