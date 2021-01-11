<?php

namespace app\model\command;

use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadTrait;

class RegisterPrize extends Command
{
    use PayloadTrait;

    public function email(): string
    {
        return $this->payload()['email'];
    }

    public function type(): string
    {
        return $this->payload()['type'];
    }

    public function details(): array
    {
        return $this->payload()['details'];
    }
}