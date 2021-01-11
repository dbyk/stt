<?php

declare(strict_types=1);

namespace app\model\event;


use Prooph\EventSourcing\AggregateChanged;

class PrizeGenerated extends AggregateChanged
{
    public function type(): string
    {
        return $this->payload()['type'];
    }

    public function email(): string
    {
        return $this->payload()['email'];
    }

    public function details(): array
    {
        return $this->payload()['details'];
    }
}