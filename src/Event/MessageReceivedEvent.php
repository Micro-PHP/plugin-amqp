<?php

namespace Micro\Plugin\Amqp\Event;

use Micro\Plugin\Amqp\Business\Message\MessageReceivedInterface;

class MessageReceivedEvent extends AbstractMessageReceivedEvent implements MessageReceivedEventInterface
{
}
