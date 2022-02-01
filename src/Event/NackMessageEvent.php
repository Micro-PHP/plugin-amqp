<?php

namespace Micro\Plugin\Amqp\Event;

use Micro\Plugin\Amqp\Business\Message\MessageInterface;

class NackMessageEvent extends AbstractMessageReceivedEvent implements NackMessageEventInterface
{
}
