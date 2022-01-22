<?php

namespace Micro\Plugin\Amqp;

use http\Exception\RuntimeException;
use Micro\Framework\Kernel\Configuration\Exception\InvalidConfigurationException;
use Micro\Framework\Kernel\Configuration\PluginConfiguration;
use Micro\Plugin\Amqp\Business\Channel\ChannelConfiguration;
use Micro\Plugin\Amqp\Business\Channel\ChannelConfigurationInterface;
use Micro\Plugin\Amqp\Business\Connection\ConnectionConfiguration;
use Micro\Plugin\Amqp\Business\Connection\ConnectionConfigurationInterface;
use Micro\Plugin\Amqp\Business\Exchange\ExchangeConfiguration;
use Micro\Plugin\Amqp\Business\Exchange\ExchangeConfigurationInterface;
use Micro\Plugin\Amqp\Business\Publisher\MessagePublisherConfiguration;
use Micro\Plugin\Amqp\Business\Publisher\MessagePublisherConfigurationInterface;
use Micro\Plugin\Amqp\Business\Queue\QueueConfiguration;
use Micro\Plugin\Amqp\Business\Queue\QueueConfigurationInterface;

class AmqpPluginConfiguration extends PluginConfiguration
{
    private const CFG_CONNECTION_NAMES_LIST = 'AMQP_CONNECTIONS';
    private const CFG_QUEUE_LIST = 'AMQP_QUEUE_LIST';
    private const CFG_EXCHANGE_LIST = 'AMQP_EXCHANGE_LIST';
    private const CFG_CHANNEL_LIST = 'AMQP_CHANNEL_LIST';
    private const CFG_PUBLISHER_LIST = 'AMQP_PUBLISHER_LIST';

    public const EXCHANGE_DEFAULT = 'default';
    public const QUEUE_DEFAULT = 'default';
    public const CHANNEL_DEFAULT = 'default';
    public const CONNECTION_DEFAULT = 'default';
    public const PUBLISHER_DEFAULT = 'default';

    /**
     * @return string[]
     */
    public function getPublisherList(): array
    {
        $publisherList = $this->configuration->get(self::CFG_PUBLISHER_LIST, self::PUBLISHER_DEFAULT);

        return $this->explodeStringToArray($publisherList);
    }

    public function getPublisherConfiguration(string $publisherName): MessagePublisherConfigurationInterface
    {
        if(!in_array($publisherName, $this->getConnectionList(), true)) {
            $this->throwInvalidArgumentException('Publisher is not defined in the environment file. Please, append connection id to "%s"', self::CFG_PUBLISHER_LIST);
        }

        return new MessagePublisherConfiguration($this->configuration, $publisherName);
    }

    /**
     * @param  string $connectionName
     * @return ConnectionConfigurationInterface
     */
    public function getConnectionConfiguration(string $connectionName): ConnectionConfigurationInterface
    {
        if(!in_array($connectionName, $this->getConnectionList(), true)) {
            $this->throwInvalidArgumentException('Connection is not defined in the environment file. Please, append connection id to "%s"', self::CFG_CHANNEL_LIST);
        }

        return new ConnectionConfiguration($this->configuration, $connectionName);
    }

    /**
     * @param  string $queueName
     * @return QueueConfigurationInterface
     */
    public function getQueueConfiguration(string $queueName): QueueConfigurationInterface
    {
        if(!in_array($queueName, $this->getQueueList(), true)) {
            $this->throwInvalidArgumentException('Queue is not defined in the environment file. Please, append queue id to "%s"', self::CFG_QUEUE_LIST);
        }

        return new QueueConfiguration($this->configuration, $queueName);
    }

    /**
     * @param  string $exchangeName
     * @return ExchangeConfigurationInterface
     */
    public function getExchangeConfiguration(string $exchangeName): ExchangeConfigurationInterface
    {
        if(!in_array($exchangeName, $this->getExchangeList(), true)) {
            $this->throwInvalidArgumentException('Exchange is not defined in the environment file. Please, append channel id to "%s"', self::CFG_EXCHANGE_LIST);
        }

        return new ExchangeConfiguration($this->configuration, $exchangeName);
    }

    /**
     * @param  string $channelName
     * @return ChannelConfigurationInterface
     */
    public function getChannelConfiguration(string $channelName): ChannelConfigurationInterface
    {
        if(!in_array($channelName, $this->getChannelList(), true)) {
            $this->throwInvalidArgumentException('Channel is not defined in the environment file. Please, append channel id to "%s"', self::CFG_CHANNEL_LIST);
        }

        return new ChannelConfiguration($this->configuration, $channelName);
    }

    /**
     * @return array
     */
    public function getConnectionList(): array
    {
        $connListString = $this->configuration->get(self::CFG_CONNECTION_NAMES_LIST, self::CONNECTION_DEFAULT);

        return $this->explodeStringToArray($connListString);
    }

    /**
     * @return array
     */
    public function getExchangeList(): array
    {
        $list = $this->configuration->get(self::CFG_EXCHANGE_LIST, self::EXCHANGE_DEFAULT);

        return $this->explodeStringToArray($list);
    }

    /**
     * @return array
     */
    public function getQueueList(): array
    {
        $list = $this->configuration->get(self::CFG_QUEUE_LIST, self::QUEUE_DEFAULT);

        return $this->explodeStringToArray($list);
    }

    /**
     * @return array
     */
    public function getChannelList(): array
    {
        $list = $this->configuration->get(self::CFG_CHANNEL_LIST, self::CHANNEL_DEFAULT);

        return $this->explodeStringToArray($list);
    }

    /**
     * @param  string $message
     * @param  ...$arguments
     * @return void
     */
    protected function throwInvalidArgumentException(string $message, ...$arguments)
    {
        throw new InvalidConfigurationException(
            sprintf($message, ...$arguments)
        );
    }

}
