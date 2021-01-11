<?php

namespace tests\unit;

use app\App;
use app\model\event\MoneyPrizeGenerated;
use app\model\Prize;
use Prooph\EventSourcing\AggregateChanged;

class MoneyPrizeTest extends PrizeTest
{

    public function testGeneration()
    {
        $amount = random_int(1, 99999);
        $prize = Prize::newPrize($this->email, 'money', ['amount' => $amount]);


        /** @var AggregateChanged[] $events */
        $events = $this->popRecordedEvents($prize);

        $this->assertCount(1, $events);
        /** @var MoneyPrizeGenerated $event */
        $event = $events[0];
        $this->assertSame(MoneyPrizeGenerated::class, $event->messageName());
        $this->assertEquals($this->email, $event->email());

        $pcEvents = $this->popRecordedEvents(App::prizesConfiguration());

        $this->assertCount(1, $pcEvents);
        /** @var MoneyPrizeGenerated $event */
        $pcEvent = $pcEvents[0];
        $this->assertSame(MoneyPrizeGenerated::class, $pcEvent->messageName());
        $this->assertEquals($amount, $event->amount());
    }
}