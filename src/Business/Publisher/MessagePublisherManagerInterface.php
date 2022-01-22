<?php

namespace Micro\Plugin\Amqp\Business\Publisher;

interface MessagePublisherManagerInterface
{
    /**
     * @param  string $publisherName
     * @return MessagePublisherInterface
     */
    public function getPublisher(string $publisherName): MessagePublisherInterface;
}
