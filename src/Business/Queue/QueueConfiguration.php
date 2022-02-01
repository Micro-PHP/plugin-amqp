<?php

namespace Micro\Plugin\Amqp\Business\Queue;

use Micro\Plugin\Amqp\AbstractAmqpComponentConfiguration;
use Micro\Plugin\Amqp\AmqpPluginConfiguration;

class QueueConfiguration extends AbstractAmqpComponentConfiguration implements QueueConfigurationInterface
{
    private const CFG_PASSIVE = 'AMQP_QUEUE_%s_PASSIVE';
    private const CFG_DURABLE = 'AMQP_QUEUE_%s_DURABLE';
    private const CFG_EXCLUSIVE = 'AMQP_QUEUE_%s_EXCLUSIVE';
    private const CFG_AUTO_DELETE = 'AMQP_QUEUE_%s_AUTO_DELETE';
    private const CFG_CONNECTION_LIST = 'AMQP_QUEUE_%s_CONNECTIONS';

    /**
     * {@inheritDoc}
     */
    public function isPassive(): bool
    {
        return $this->get(self::CFG_PASSIVE, false);
    }

    /**
     * {@inheritDoc}
     */
    public function isDurable(): bool
    {
        return $this->get(self::CFG_DURABLE, true);
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
    public function getName(): string
    {
        return $this->configRoutingKey;
    }

    /**
     * {@inheritDoc}
     */
    public function isAutoDelete(): bool
    {
        return $this->get(self::CFG_AUTO_DELETE, false);
    }

    /**
     * {@inheritDoc}
     */
    public function getConnectionList(): array
    {
        $connectionListSource = $this->get(self::CFG_CONNECTION_LIST, AmqpPluginConfiguration::CONNECTION_DEFAULT);

        return $this->explodeSource($connectionListSource);
    }

    /**
     * {@inheritDoc}
     */
    public function getChannelList(): array
    {
        $channelList = $this->get(self::CFG_CONNECTION_LIST, AmqpPluginConfiguration::CHANNEL_DEFAULT);

        return $this->explodeSource($channelList);
    }

    /**
     * @param  string $source
     * @param  string $separator
     * @return string[]
     */
    protected function explodeSource(string $source, string $separator = ','): array
    {
        return array_map('trim', explode($separator, $source));
    }
}
