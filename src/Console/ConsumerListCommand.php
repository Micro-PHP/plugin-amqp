<?php

namespace Micro\Plugin\Amqp\Console;

use Micro\Plugin\Amqp\AmqpPluginConfiguration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsumerListCommand extends Command
{
    protected static $defaultName = 'micro:amqp:consumer:list';
    protected const HELP          = 'This command show all registered consumers whti settings.';

    /**
     * @param AmqpPluginConfiguration $pluginConfiguration
     */
    public function __construct(private AmqpPluginConfiguration $pluginConfiguration)
    {
        parent::__construct(self::$defaultName);
    }

    /**
     * {@inheritDoc}
     */
    protected function configure(): void
    {
        $this->setHelp(self::HELP);
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $table = new Table($output);
        $table->setHeaders([
            'Consumer', 'Channel', 'Queue', 'Connection', 'Tag'
        ]);

        $consumerList = $this->pluginConfiguration->getConsumerList();
        foreach ($consumerList as $consumer) {
            $config = $this->pluginConfiguration->getConsumerConfiguration($consumer);
            $table->addRow([
                $consumer,
                $config->getChannel(),
                $config->getQueue(),
                $config->getConnection(),
                $config->getTag(),
            ]);
        }

        $table->render();

        return Command::SUCCESS;
    }
}
