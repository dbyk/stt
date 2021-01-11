<?php

namespace tests\unit;

use app\App;
use app\model\event\PrizeCanceled;
use app\model\event\PrizeSent;
use app\model\Prize;
use Prooph\EventSourcing\AggregateChanged;

class PrizeMethodsTest extends PrizeTest
{

    public function testSendMethod()
    {
        /** @var Prize $prize */
        $prize = $this->make(Prize::class, ['email' => $this->email, 'sent' => false]);
        $this->assertFalse($prize->sent);
        $prize->send();
        $this->assertTrue($prize->sent);

        /** @var AggregateChanged[] $events */
        $events = $this->popRecordedEvents($prize);
        $this->assertCount(1, $events);
        /** @var PrizeSent $event */
        $event = $events[0];
        $this->assertSame(PrizeSent::class, $event->messageName());
    }

    public function testSendAgainMethod()
    {
        /** @var Prize $prize */
        $prize = $this->make(Prize::class, ['email' => $this->email, 'sent' => true]);
        $this->assertTrue($prize->sent);
        $prize->send();
        $this->assertTrue($prize->sent);

        /** @var AggregateChanged[] $events */
        $events = $this->popRecordedEvents($prize);
        $this->assertCount(0, $events);
    }

    public function testCancelMethod()
    {
        /** @var Prize $prize */
        $prize = $this->make(Prize::class, ['email' => $this->email, 'cancelled' => false]);
        $this->assertFalse($prize->cancelled);
        $prize->cancel();
        $this->assertTrue($prize->cancelled);

        /** @var AggregateChanged[] $events */
        $events = $this->popRecordedEvents($prize);
        $this->assertCount(1, $events);
        /** @var PrizeCanceled $event */
        $event = $events[0];
        $this->assertSame(PrizeCanceled::class, $event->messageName());


        $pcEvents = $this->popRecordedEvents(App::prizesConfiguration());
        $this->assertCount(1, $pcEvents);
        /** @var PrizeCanceled $event */
        $pcEvent = $pcEvents[0];
        $this->assertSame(PrizeCanceled::class, $pcEvent->messageName());
    }
}