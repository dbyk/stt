<?php

declare(strict_types=1);

namespace app\model\event;


class ItemPrizeGenerated extends PrizeGenerated
{
    public function itemName(): string
    {
        return $this->details()['itemName'];
    }
}