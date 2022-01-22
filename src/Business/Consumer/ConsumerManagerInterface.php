<?php

namespace Micro\Plugin\Amqp\Business\Consumer;

interface ConsumerManagerInterface
{
    /**
     * @param string $consumerName
     * @return ConsumerInterface
     */
    public function getConsumer(string $consumerName): ConsumerInterface;
}
