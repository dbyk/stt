<?php

use app\App;
use app\model\PrizesConfiguration;
use app\prizeGenerators\PrizeGenerator;
use Codeception\Test\Unit;

class PrizesGeneratorTest extends Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    private string $email;

    private PrizesConfiguration $pc;

    protected function _before()
    {
        $this->email = 'email_' . microtime(true) . '@test.email';
        $this->pc = App::prizesConfiguration();
    }

    public function testGenerateMoneyPrize()
    {
        $pg = new PrizeGenerator($this->pc);
        $pg->generate($this->email, 'money');
        $prize = App::$prizeRepository->get($this->email);
        $amount = $prize->details['amount'];

        $this->assertTrue($amount >= $this->pc->money['min']);
        $this->assertTrue($amount <= $this->pc->money['max']);
    }

    public function testGenerateBonusesPrize()
    {
        $pg = new PrizeGenerator($this->pc);
        $pg->generate($this->email, 'bonuses');
        $prize = App::$prizeRepository->get($this->email);
        $amount = $prize->details['amount'];

        $this->assertTrue($amount >= $this->pc->bonuses['min']);
        $this->assertTrue($amount <= $this->pc->bonuses['max']);
    }

    public function testGenerateItemPrize()
    {
        $pg = new PrizeGenerator($this->pc);
        $pg->generate($this->email, 'item');
        $prize = App::$prizeRepository->get($this->email);

        $this->assertTrue(in_array($prize->details['itemName'], $this->pc->itemNames()));
    }
}