<?php

declare(strict_types=1);

namespace app\model\event;

use Prooph\EventSourcing\AggregateChanged;

class MoneyPrizeConvertedToBonus extends AggregateChanged
{
    public function moneyAmount(): int
    {
        return $this->payload()['moneyAmount'];
    }

    public function bonusesAmount(): int
    {
        return $this->payload()['bonusesAmount'];
    }
}