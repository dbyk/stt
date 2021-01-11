<?php


namespace app\console;


use app\App;
use app\model\command\SetPrizesConfiguration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PrizesConfigurationInit extends Command
{
    protected static $defaultName = 'app:prizes-config-init';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        App::$commandBus->dispatch(new SetPrizesConfiguration([
            'money' => [
                'left' => 1000000,
                'min' => 100,
                'max' => 1000,
                'bonusRate' => 2,
            ],
            'bonuses' => [
                'min' => 100,
                'max' => 1000,
            ],
            'item' => [
                [
                    'name' => 'Headset',
                    'left' => 1000,
                ],
                [
                    'name' => 'Smartphone',
                    'left' => 100,
                ],
                [
                    'name' => 'Laptop',
                    'left' => 10,
                ],
                [
                    'name' => 'Car',
                    'left' => 1,
                ],
            ],
        ]));
        $output->writeln("Prizes configuration was set");
        return Command::SUCCESS;
    }
}