<?php

declare(strict_types=1);

namespace app\model\event;


class MoneyPrizeGenerated extends PrizeGenerated
{
    public function amount(): int
    {
        return $this->details()['amount'];
    }
}