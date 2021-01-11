<?php

namespace tests\unit;

use app\App;
use app\model\event\BonusesPrizeGenerated;
use app\model\Prize;
use Prooph\EventSourcing\AggregateChanged;

class BonusesPrizeTest extends PrizeTest
{
    public function testGeneration()
    {
        $amount = random_int(1, 99999);
        $prize = Prize::newPrize($this->email, 'bonuses', ['amount' => $amount]);


        /** @var AggregateChanged[] $events */
        $events = $this->popRecordedEvents($prize);

        $this->assertCount(1, $events);
        /** @var BonusesPrizeGenerated $event */
        $event = $events[0];
        $this->assertSame(BonusesPrizeGenerated::class, $event->messageName());
        $this->assertEquals($this->email, $event->email());

        $pcEvents = $this->popRecordedEvents(App::prizesConfiguration());

        $this->assertCount(1, $pcEvents);
        /** @var BonusesPrizeGenerated $event */
        $pcEvent = $pcEvents[0];
        $this->assertSame(BonusesPrizeGenerated::class, $pcEvent->messageName());
        $this->assertEquals($amount, $event->amount());
    }
}