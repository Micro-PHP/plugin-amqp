<?php

namespace Micro\Plugin\Amqp\Business\Publisher;

use PhpAmqpLib\Message\AMQPMessage;

interface MessagePublisherConfigurationInterface
{
    /**
     * @return string
     */
    public function getConnection(): string;

    /**
     * @return string
     */
    public function getChannel(): string;

    /**
     * @return string
     */
    public function getExchange(): string;

    /**
     * Set message content type.
     *
     * Examples: text/plain, application/json
     *
     * @return string
     */
    public function getContentType(): string;

    /**
     * Possible values:
     *      AMQPMessage::DELIVERY_MODE_NON_PERSISTENT,
     *      AMQPMessage::DELIVERY_MODE_PERSISTENT
     *
     * @return int
     */
    public function getDeliveryMode(): int;
}
