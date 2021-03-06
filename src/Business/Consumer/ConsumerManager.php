<?php

namespace Micro\Plugin\Amqp\Business\Consumer;

use Closure;
use Micro\Plugin\Amqp\AmqpPluginConfiguration;
use Micro\Plugin\Amqp\Business\Channel\ChannelManagerInterface;
use Micro\Plugin\Amqp\Business\Serializer\MessageSerializerFactoryInterface;
use Micro\Plugin\Amqp\Event\ConsumerFinishEvent;
use Micro\Plugin\Amqp\Event\ConsumerStartEvent;
use Micro\Plugin\EventEmitter\EventsFacadeInterface;
use PhpAmqpLib\Channel\AMQPChannel;

class ConsumerManager implements ConsumerManagerInterface
{
    /**
     * @var array<string, Closure[]>
     */
    private array $consumerProcessorCollection;

    /**
     * @param AmqpPluginConfiguration $pluginConfiguration
     * @param ChannelManagerInterface $channelManager
     * @param MessageSerializerFactoryInterface $messageSerializerFactory
     * @param EventsFacadeInterface $eventsFacade
     */
    public function __construct(
    private AmqpPluginConfiguration $pluginConfiguration,
    private ChannelManagerInterface $channelManager,
    private MessageSerializerFactoryInterface $messageSerializerFactory,
    private EventsFacadeInterface $eventsFacade
    )
    {
        $this->consumerProcessorCollection = [];
    }

    /**
     * @param string $consumerName
     * @return void
     * @throws \ErrorException
     */
    public function consume(string $consumerName = AmqpPluginConfiguration::CONSUMER_DEFAULT): void
    {
        $this->eventsFacade->emit(new ConsumerStartEvent($consumerName));
        $channel = $this->declareConsumer($consumerName);
        while ($channel->is_consuming()) {
            $channel->wait();
        }

        $this->eventsFacade->emit(new ConsumerFinishEvent($consumerName));
    }

    /**
     * @param string $consumerName
     * @return AMQPChannel
     */
    protected function declareConsumer(string $consumerName): AMQPChannel
    {
        $processorCollection = $this->getConsumerProcessorCollection($consumerName);
        $configuration       = $this->pluginConfiguration->getConsumerConfiguration($consumerName);
        $channel             = $this->channelManager->getChannel($configuration->getChannel(), $configuration->getConnection());

        foreach ($processorCollection as $processor) {
            $this->appendChannelConsumer($channel, $configuration, $processor);
        }

        return $channel;
    }

    /**
     * @param string $consumerName
     * @return array|ConsumerProcessorInterface|mixed
     */
    protected function getConsumerProcessorCollection(string $consumerName)
    {
        if(!array_key_exists($consumerName, $this->consumerProcessorCollection)) {
            return [];
        }

        return $this->consumerProcessorCollection[$consumerName];
    }

    /**
     * @param AMQPChannel $channel
     * @param ConsumerConfigurationInterface $configuration
     * @param Closure $processor
     * @return string
     */
    protected function appendChannelConsumer(AMQPChannel $channel, ConsumerConfigurationInterface $configuration , Closure $processor): string
    {
        return $channel->basic_consume(
            $configuration->getQueue(),
            $configuration->getTag(),
            $configuration->isNoLocal(),
            $configuration->isNoAck(),
            $configuration->isExclusive(),
            $configuration->isNoWait(),
            $processor
        );
    }

    /**
     * {@inheritDoc}
     */
    public function registerConsumerProcessor(
        ConsumerProcessorInterface $consumerProcessor,
        string $consumerName = AmqpPluginConfiguration::CONSUMER_DEFAULT
    ): void
    {
        if(!array_key_exists($consumerName, $this->consumerProcessorCollection)) {
            $this->consumerProcessorCollection[$consumerName] = [];
        }

        $amqpProcessor = $this->createProcessorProxyBuilder()->createProxy($consumerProcessor);

        $this->consumerProcessorCollection[$consumerName][] = $amqpProcessor;
    }

    /**
     * @return ConsumerProcessorProxyBuilder
     */
    protected function createProcessorProxyBuilder(): ConsumerProcessorProxyBuilder
    {
        return new ConsumerProcessorProxyBuilder(
            $this->messageSerializerFactory,
            $this->eventsFacade
        );
    }
}
