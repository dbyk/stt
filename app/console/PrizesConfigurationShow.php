<?php

namespace app\console;

use app\App;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PrizesConfigurationShow extends Command
{
    protected static $defaultName = 'app:prizes-config';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach (App::prizesConfiguration()->asPayload() as $type => $data) {
            $output->writeln("Prize type: '$type'");
            $output->writeln(print_r($data, true));
        }
        return Command::SUCCESS;
    }
}