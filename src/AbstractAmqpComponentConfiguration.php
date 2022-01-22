<?php

namespace Micro\Plugin\Amqp;

use Micro\Framework\Kernel\Configuration\ApplicationConfigurationInterface;

class AbstractAmqpComponentConfiguration
{
    public function __construct(
    protected ApplicationConfigurationInterface $configuration,
    protected string $configRoutingKey
    ) {

    }

    /**
     * @param  string $key
     * @return string
     */
    protected function cfg(string $key): string
    {
        return sprintf($key, mb_strtoupper($this->configRoutingKey));
    }

    /**
     * @param  string $key
     * @param  $default
     * @return mixed
     */
    protected function get(string $key, $default = null): mixed
    {
        return $this->configuration->get(
            $this->cfg($key),
            $default
        );
    }
}
