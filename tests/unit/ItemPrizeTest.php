<?php

namespace tests\unit;

use app\App;
use app\model\event\ItemPrizeGenerated;
use app\model\Prize;
use Prooph\EventSourcing\AggregateChanged;

class ItemPrizeTest extends PrizeTest
{

    public function testGeneration()
    {
        $itemName = 'Something real';
        $prize = Prize::newPrize($this->email, 'item', ['itemName' => $itemName]);

        /** @var AggregateChanged[] $events */
        $events = $this->popRecordedEvents($prize);

        $this->assertCount(1, $events);
        /** @var ItemPrizeGenerated $event */
        $event = $events[0];
        $this->assertSame(ItemPrizeGenerated::class, $event->messageName());
        $this->assertEquals($this->email, $event->email());

        $pcEvents = $this->popRecordedEvents(App::prizesConfiguration());

        $this->assertCount(1, $pcEvents);
        /** @var ItemPrizeGenerated $pcEvent */
        $pcEvent = $pcEvents[0];
        $this->assertSame(ItemPrizeGenerated::class, $pcEvent->messageName());
        $this->assertEquals($itemName, $event->itemName());
    }
}