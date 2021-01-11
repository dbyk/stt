<?php


namespace app\console;


use app\App;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendMoneyPrizes extends Command
{
    protected static $defaultName = 'app:send-money-prizes';

    protected function configure()
    {
        parent::configure();
        $this->addArgument('n', InputArgument::REQUIRED, 'Batch size');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $n = $input->getArgument('n');
        echo "Sending by $n\n";
        // @todo send many requests to the bank
        return Command::SUCCESS;
    }
}