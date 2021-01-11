<?php

declare(strict_types=1);

namespace app\model\event;


class BonusesPrizeGenerated extends PrizeGenerated
{
    public function amount(): int
    {
        return $this->details()['amount'];
    }
}