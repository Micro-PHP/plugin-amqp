<?php

namespace Micro\Plugin\Amqp\Business\Exchange;

use Micro\Plugin\Amqp\AbstractAmqpComponentConfiguration;
use Micro\Plugin\Amqp\AmqpPluginConfiguration;
use PhpAmqpLib\Exchange\AMQPExchangeType;

class ExchangeConfiguration extends AbstractAmqpComponentConfiguration implements ExchangeConfigurationInterface
{
    private const CFG_TYPE           = 'AMQP_EXCHANGE_%s_TYPE';
    private const CFG_IS_PASSIVE     = 'AMQP_EXCHANGE_%s_PASSIVE';
    private const CFG_IS_DURABLE     = 'AMQP_EXCHANGE_%s_DURABLE';
    private const CFG_IS_AUTO_DELETE = 'AMQP_EXCHANGE_%s_AUTO_DELETE';
    private const CFG_IS_INTERNAL    = 'AMQP_EXCHANGE_%s_INTERNAL';
    private const CFG_IS_NO_WAIT     = 'AMQP_EXCHANGE_%s_NO_WAIT';
    private const CFG_TICKET         ='AMQP_EXCHANGE_%s_TICKET';
    private const CFG_CHANNELS       = 'AMQP_EXCHANGE_%s_CHANNELS';
    private const CFG_CONNECTIONS    = 'AMQP_EXCHANGE_%s_CONNECTIONS';

    /**
     * {@inheritDoc}
     */
    public function getType(): string
    {
        $type = mb_strtoupper($this->get(self::CFG_TYPE, 'DIRECT'));

        return constant(AMQPExchangeType::class . '::' . $type);
    }

    /**
     * {@inheritDoc}
     */
    public function isPassive(): bool
    {
        return $this->get(self::CFG_IS_PASSIVE, false);
    }

    /**
     * {@inheritDoc}
     */
    public function isDurable(): bool
    {
        return $this->get(self::CFG_IS_DURABLE, true);
    }

    /**
     * {@inheritDoc}
     */
    public function isAutoDelete(): bool
    {
        return $this->get(self::CFG_IS_AUTO_DELETE, false);
    }

    /**
     * {@inheritDoc}
     */
    public function isInternal(): bool
    {
        return $this->get(self::CFG_IS_INTERNAL, false);
    }

    /**
     * {@inheritDoc}
     */
    public function isNoWait(): bool
    {
        return $this->get(self::CFG_IS_NO_WAIT, true);
    }

    /**
     * {@inheritDoc}
     *
     * @TODO:
     */
    public function getArguments(): array
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function getTicket(): ?int
    {
        $value = $this->get(self::CFG_TICKET);

        return $value ? (int)$value: null;
    }

    /**
     * @return array|string[]
     */
    public function getConnectionList(): array
    {
        $connectionList = $this->get(self::CFG_CONNECTIONS, AmqpPluginConfiguration::CONNECTION_DEFAULT);

        return $this->explodeStringToArray($connectionList);
    }

    /**
     * @return string[]
     */
    public function getChannelList(): array
    {
        $channelList = $this->get(self::CFG_CHANNELS, AmqpPluginConfiguration::CHANNEL_DEFAULT);

        return $this->explodeStringToArray($channelList);
    }
}
