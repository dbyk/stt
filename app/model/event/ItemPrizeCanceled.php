<?php

declare(strict_types=1);

namespace app\model\event;


class ItemPrizeCanceled extends PrizeGenerated
{
    public function itemName(): string
    {
        return $this->payload()['itemName'];
    }
}