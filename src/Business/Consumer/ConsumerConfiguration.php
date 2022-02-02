<?php

namespace Micro\Plugin\Amqp\Business\Consumer;

use Micro\Plugin\Amqp\AbstractAmqpComponentConfiguration;
use Micro\Plugin\Amqp\AmqpPluginConfiguration;

class ConsumerConfiguration extends AbstractAmqpComponentConfiguration implements ConsumerConfigurationInterface
{

    private const CFG_TAG        = 'AMQP_CONSUMER_%s_TAG';
    private const CFG_CHANNEL    = 'AMQP_CONSUMER_%s_CHANNEL';
    private const CFG_CONNECTION = 'AMQP_CONSUMER_%s_CONNECTION';
    private const CFG_QUEUE      = 'AMQP_CONSUMER_%s_QUEUE';
    private const CFG_NO_WAIT    = 'AMQP_CONSUMER_%s_NO_WAIT';
    private const CFG_EXCLUSIVE  = 'AMQP_CONSUMER_%s_EXCLUSIVE';
    private const CFG_NO_ACK     = 'AMQP_CONSUMER_%s_NO_ACK';
    private const CFG_NO_LOCAL   = 'AMQP_CONSUMER_%s_NO_LOCAL';



    /**
     * @return string
     */
    public function getQueue(): string
    {
        return $this->get(self::CFG_QUEUE, AmqpPluginConfiguration::QUEUE_DEFAULT);
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
    public function getConnection(): string
    {
        return $this->get(self::CFG_CONNECTION, AmqpPluginConfiguration::CONNECTION_DEFAULT);
    }

    /**
     * {@inheritDoc}
     */
    public function getTag(): string
    {
        return $this->get(self::CFG_TAG, '');
    }

    /**
     * {@inheritDoc}
     */
    public function isNoWait(): bool
    {
        return $this->get(self::CFG_NO_WAIT, false);
    }

    /**
     * {@inheritDoc}
     */
    public function isExclusive(): bool
    {
        return $this->get(self::CFG_EXCLUSIVE, false);
    }

    /**
     * {@inheritDoc}
     */
    public function isNoAck(): bool
    {
        return $this->get(self::CFG_NO_ACK, false);
    }

    /**
     * {@inheritDoc}
     */
    public function isNoLocal(): bool
    {
        return $this->get(self::CFG_NO_LOCAL, false);
    }
}
