<?php

namespace app\model\command;

use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadTrait;

class CancelPrize extends Command
{
    use PayloadTrait;

    public function email(): string
    {
        return $this->payload()['email'];
    }

}