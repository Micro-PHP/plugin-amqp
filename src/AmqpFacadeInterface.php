<?php

namespace Micro\Plugin\Amqp;

use Micro\Plugin\Amqp\Business\Consumer\ConsumerManagerInterface;
use Micro\Plugin\Amqp\Business\Message\MessageInterface;
use Micro\Plugin\Amqp\Business\Publisher\PublisherManagerInterface;

interface AmqpFacadeInterface extends ConsumerManagerInterface, PublisherManagerInterface
{
    /**
     * @return void
     */
    public function terminate(): void;

    /**
     * @param MessageInterface $message
     * @return string
     */
    public function serializeMessage(MessageInterface $message): string;

    /**
     * @param string $messageContent
     * @return MessageInterface
     */
    public function deserializeMessage(string $messageContent): MessageInterface;
}
