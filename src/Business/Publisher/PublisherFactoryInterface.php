<?php

namespace Micro\Plugin\Amqp\Business\Publisher;

interface PublisherFactoryInterface
{
    /**
     * @return PublisherInterface
     */
    public function create(string $publisherName): PublisherInterface;
}
