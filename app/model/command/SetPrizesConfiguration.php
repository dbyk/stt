<?php

namespace app\model\command;

use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadTrait;

class SetPrizesConfiguration extends Command
{
    use PayloadTrait;

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