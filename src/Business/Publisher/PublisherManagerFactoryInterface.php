<?php

namespace Micro\Plugin\Amqp\Business\Publisher;

interface PublisherManagerFactoryInterface
{
    /**
     * @return PublisherManagerInterface
     */
    public function create(): PublisherManagerInterface;
}
