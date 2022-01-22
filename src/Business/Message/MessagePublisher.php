<?php

namespace Micro\Plugin\Amqp\Business\Message;

use Micro\Plugin\Amqp\Business\Channel\ChannelManagerInterface;
use Micro\Plugin\Amqp\Business\Exchange\ExchangeManagerInterface;
use Micro\Plugin\Amqp\Business\Queue\QueueManagerInterface;

class MessagePublisher implements MessagePublisherInterface
{
    public function __construct(
    private ExchangeManagerInterface $exchangeManager,
    private ChannelManagerInterface $channelManager,
    private QueueManagerInterface $queueManager
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function publish(MessageInterface $message): void
    {
        $channel = $this->channelManager->getChannel();
    }
}
