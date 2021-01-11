<?php

namespace app\prizeGenerators;

use app\model\Prize;

class ItemPrizeGenerator implements PrizeGeneratorInterface
{
    private array $config;

    private int $totalLeft = -1;

    private array $itemsAvailable;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->itemsAvailable = array_filter($config, function ($item) {
            return $item['left'] > 0;
        });
        $this->totalLeft = array_sum(array_map(function ($el) {
            return $el['left'];
        }, $this->itemsAvailable));
    }

    public function isAvailable(): bool
    {
        return $this->totalLeft > 0;
    }

    public function getPrizeDetailsIfAvailable(): array
    {
        $itemIndex = random_int(0, count($this->itemsAvailable) - 1);
        return [
            'itemName' => $this->itemsAvailable[$itemIndex]['name'],
        ];
    }
}