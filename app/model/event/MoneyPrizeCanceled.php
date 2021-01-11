<?php

declare(strict_types=1);

namespace app\model\event;


class MoneyPrizeCanceled extends PrizeGenerated
{

    public function moneyAmount(): int
    {
        return $this->payload()['moneyAmount'];
    }
}