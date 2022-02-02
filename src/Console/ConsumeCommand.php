<?php

namespace Micro\Plugin\Amqp\Console;

use Micro\Plugin\Amqp\AmqpFacadeInterface;
use Micro\Plugin\Amqp\AmqpPluginConfiguration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsumeCommand extends Command
{
    protected static $defaultName = 'micro:amqp:consume';
    protected const ARG_CONSUMER  = 'consumer';
    protected const HELP          = 'This command run consumer. ';

    /**
     * @param AmqpFacadeInterface $amqpFacade
     */
    public function __construct(private AmqpFacadeInterface $amqpFacade)
    {
        parent::__construct(self::$defaultName);
    }

    /**
     * {@inheritDoc}
     */
    protected function configure(): void
    {
        $this->setHelp(self::HELP);
        $this->addArgument(
            self::ARG_CONSUMER,
            InputArgument::OPTIONAL,
            'Consumer name',
            AmqpPluginConfiguration::CONSUMER_DEFAULT);
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $consumerName = $input->getArgument(self::ARG_CONSUMER);
        $output->writeln(sprintf('Launch of consumer "%s"', $consumerName));
        $this->amqpFacade->consume($input->getArgument(self::ARG_CONSUMER));
        $output->writeln(sprintf('Completion of the work of the consumer "%s"', $consumerName));

        return Command::SUCCESS;
    }
}
