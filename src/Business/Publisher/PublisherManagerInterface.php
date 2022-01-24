<?php

namespace Micro\Plugin\Amqp\Business\Publisher;

use Micro\Plugin\Amqp\AmqpPluginConfiguration;
use Micro\Plugin\Amqp\Business\Message\MessageInterface;

interface PublisherManagerInterface
{
    /**
     * @param MessageInterface $message
     * @param string $publisherName
     * @return void
     */
    public function publish(MessageInterface $message, string $publisherName = AmqpPluginConfiguration::PUBLISHER_DEFAULT): void;
}
