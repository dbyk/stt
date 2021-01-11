<?php

namespace tests\unit;

use app\App;
use tests\UnitProophTest;

abstract class PrizeTest extends UnitProophTest
{
    public string $email;
    
    protected function _before()
    {
        $this->email = 'email_' . microtime(true) . '@test.email';
        $app = new App();
        $app->initProoph();
    }


}