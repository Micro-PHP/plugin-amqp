<?php

namespace Micro\Plugin\Amqp\Event;

interface PublishMessageEventInterface extends MessageActionEventInterface
{
    /**
     * @return string
     */
    public function getExchange(): string;

    /**
     * @return string
     */
    public function getChannel(): string;

    /**
     * @return string
     */
    public function getConnection(): string;

    /**
     * @return string
     */
    public function getContentType(): string;

    /**
     * @return int
     */
    public function getDeliveryMode(): int;
}
