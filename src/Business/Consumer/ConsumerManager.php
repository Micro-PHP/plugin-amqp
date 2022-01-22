<?php

namespace Micro\Plugin\Amqp\Business\Consumer;

use Micro\Plugin\Amqp\AmqpPluginConfiguration;

class ConsumerManager implements ConsumerManagerInterface
{
    /**
     * @var array<string, ConsumerInterface>
     */
    private array $consumerCollection;

    public function __construct(private AmqpPluginConfiguration $pluginConfiguration)
    {
        $this->consumerCollection = [];
    }

    /**
     * @param string $consumerName
     *
     * @return ConsumerInterface
     */
    public function getConsumer(string $consumerName): ConsumerInterface
    {
        if(!empty($this->consumerCollection[$consumerName])) {
            return $this->consumerCollection[$consumerName];
        }

        $this->consumerCollection[$consumerName] = $this->createConsumerCallback();
    }

    protected function createConsumerCallback(): \Closure
    {
        //$this->pluginConfiguration->getCons

        //return function() {};
    }
}
