<?php


namespace app\prizeGenerators;

class BonusesPrizeGenerator implements PrizeGeneratorInterface
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function isAvailable(): bool
    {
        // bonuses are always available
        return true;
    }

    public function getPrizeDetailsIfAvailable(): array
    {
        return [
            'amount' => random_int($this->config['min'], $this->config['max']),
        ];
    }
}