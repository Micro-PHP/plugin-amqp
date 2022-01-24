<?php

namespace Micro\Plugin\Amqp;

use Micro\Plugin\Amqp\Business\Consumer\ConsumerManagerInterface;
use Micro\Plugin\Amqp\Business\Publisher\PublisherManagerInterface;

interface AmqpFacadeInterface extends ConsumerManagerInterface, PublisherManagerInterface
{
}
