<?php

namespace Micro\Plugin\Amqp\Console;

use Micro\Plugin\Amqp\AmqpPluginConfiguration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PublisherListCommand extends Command
{
    protected static $defaultName = 'micro:amqp:publisher:list';
    protected const HELP = 'This command show all registered publishers with settings.';

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
            'Publisher', 'Channel', 'Exchange', 'Connection', 'Delivery Mode', 'Content Type'
        ]);

        $publisherList = $this->pluginConfiguration->getPublisherList();
        foreach ($publisherList as $publisher) {
            $config = $this->pluginConfiguration->getPublisherConfiguration($publisher);
            $table->addRow([
                $publisher,
                $config->getChannel(),
                $config->getExchange(),
                $config->getConnection(),
                $config->getDeliveryMode(),
                $config->getContentType(),
            ]);
        }

        $table->render();

        return Command::SUCCESS;
    }
}
