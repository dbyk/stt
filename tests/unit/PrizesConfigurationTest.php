<?php

namespace tests\unit;

use app\App;
use app\model\event\PrizesConfigurationUpdated;
use Prooph\EventSourcing\AggregateChanged;
use tests\UnitProophTest;

class PrizesConfigurationTest extends UnitProophTest
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testConfigurationUpdating()
    {
        $money = [
            'left' => 1000000,
            'min' => 100,
            'max' => 1000,
            'bonusRate' => 2,
        ];
        $bonuses = [
            'min' => 100,
            'max' => 1000,
        ];
                $item = [
            [
                'name' => 'Headset',
                'left' => 1000,
            ],
        ];
        App::prizesConfiguration()->updateData($money, $bonuses, $item);

        /** @var AggregateChanged[] $events */
        $events = $this->popRecordedEvents(App::prizesConfiguration());

        $this->assertCount(1, $events);
        /** @var PrizesConfigurationUpdated $event */
        $event = $events[0];
        $this->assertSame(PrizesConfigurationUpdated::class, $event->messageName());
        $this->assertEquals($money, $event->money());
        $this->assertEquals($bonuses, $event->bonuses());
        $this->assertEquals($item, $event->item());
    }
}