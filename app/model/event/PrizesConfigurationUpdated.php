<?php

declare(strict_types=1);

namespace app\model\event;


use Prooph\EventSourcing\AggregateChanged;

class PrizesConfigurationUpdated extends AggregateChanged
{
    public function money(): array
    {
        return $this->payload()['money'];
    }

    public function bonuses(): array
    {
        return $this->payload()['bonuses'];
    }

    public function item(): array
    {
        return $this->payload()['item'];
    }
}