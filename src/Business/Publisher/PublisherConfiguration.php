<?php

namespace Micro\Plugin\Amqp\Business\Publisher;

use Micro\Plugin\Amqp\AbstractAmqpComponentConfiguration;
use Micro\Plugin\Amqp\AmqpPluginConfiguration;
use PhpAmqpLib\Message\AMQPMessage;

class PublisherConfiguration extends AbstractAmqpComponentConfiguration implements PublisherConfigurationInterface
{
    private const CFG_CONNECTION    = 'AMQP_PUBLISHER_%s_CONNECTION';
    private const CFG_CHANNEL       = 'AMQP_PUBLISHER_%s_CHANNEL';
    private const CFG_EXCHANGE      = 'AMQP_PUBLISHER_%s_EXCHANGE';
    private const CFG_CONTENT_TYPE  = 'AMQP_PUBLISHER_%s_CONTENT_TYPE';
    private const CFG_DELIVERY_MODE = 'AMQP_PUBLISHER_%s_DELIVERY_MODE';

    /**
     * {@inheritDoc}
     */
    public function getConnection(): string
    {
        return $this->get(self::CFG_CONNECTION, AmqpPluginConfiguration::CONNECTION_DEFAULT);
    }

    /**
     * {@inheritDoc}
     */
    public function getChannel(): string
    {
        return $this->get(self::CFG_CHANNEL, AmqpPluginConfiguration::CHANNEL_DEFAULT);
    }

    /**
     * {@inheritDoc}
     */
    public function getExchange(): string
    {
        return $this->get(self::CFG_EXCHANGE, AmqpPluginConfiguration::EXCHANGE_DEFAULT);
    }

    /**
     * {@inheritDoc}
     */
    public function getContentType(): string
    {
        return $this->get(self::CFG_CONTENT_TYPE, 'text/plain');
    }

    /**
     * @return string
     */
    public function getDeliveryMode(): int
    {
        $deliveryModeString = $this->get(self::CFG_DELIVERY_MODE, 'DELIVERY_MODE_PERSISTENT');

        return constant(AMQPMessage::class . '::' . $deliveryModeString);
    }
}
