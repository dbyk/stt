<?php

declare(strict_types=1);

namespace app\model\event;


use Prooph\EventSourcing\AggregateChanged;

class UserRegistered extends AggregateChanged
{
    public function email(): string
    {
        return $this->payload['email'];
    }
}