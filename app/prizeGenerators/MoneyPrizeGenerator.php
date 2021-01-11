<?php

namespace app\prizeGenerators;

use app\model\Prize;

class MoneyPrizeGenerator implements PrizeGeneratorInterface
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function isAvailable(): bool
    {
        return $this->config['left'] >= $this->config['min'];
    }

    public function getPrizeDetailsIfAvailable(): array
    {
        return [
            'amount' => random_int($this->config['min'], min($this->config['max'], $this->config['left'])),
        ];
    }
}