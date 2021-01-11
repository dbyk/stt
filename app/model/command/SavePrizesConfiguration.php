<?php

namespace app\model\command;

use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadTrait;

class SavePrizesConfiguration extends Command
{
    use PayloadTrait;

}