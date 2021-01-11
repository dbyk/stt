<?php


namespace app\model;


use app\model\event\ItemPrizeCanceled;
use app\model\event\ItemPrizeGenerated;
use app\model\event\MoneyPrizeCanceled;
use app\model\event\MoneyPrizeConvertedToBonus;
use app\model\event\MoneyPrizeGenerated;
use app\model\event\PrizesConfigurationUpdated;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;

class PrizesConfiguration extends AggregateRoot
{

    public $money, $bonuses, $item;

    protected function aggregateId(): string
    {
        return '1';
    }

    public static function init(): self
    {
        return new self();
    }

    public function asPayload(): array
    {
        return [
            'money' => $this->money,
            'bonuses' => $this->bonuses,
            'item' => $this->item,
        ];
    }

    public function updateData($money, $bonuses, $item)
    {
        $this->recordThat(PrizesConfigurationUpdated::occur($this->aggregateId(), [
            'money' => $money,
            'bonuses' => $bonuses,
            'item' => $item,
        ]));
    }

    public function itemNames(): array
    {
        return array_map(function ($el) {
            return $el['name'];
        }, $this->item);
    }

    private function changeMoneyLeft(int $delta): void
    {
        $moneyLeft = $this->money['left'] ?? 0;
        $moneyLeft = max(0, $moneyLeft + $delta);
        $this->money['left'] = $moneyLeft;
    }

    private function changeItemLeft(string $itemName, int $delta = -1)
    {
        foreach ($this->item as $k => $item) {
            if ($item['name'] === $itemName) {
                $item['left'] = max(0, $item['left'] + $delta);
                $this->item[$k] = $item;
                break;
            }
        }
    }

    /**
     * @inheritDoc
     */
    protected function apply(AggregateChanged $event): void
    {
        switch (get_class($event)) {
            case PrizesConfigurationUpdated::class:
                /** @var PrizesConfigurationUpdated $event */
                $this->money = $event->money();
                $this->bonuses = $event->bonuses();
                $this->item = $event->item();
                break;
            case MoneyPrizeGenerated::class:
                /** @var MoneyPrizeGenerated $event */
                $this->changeMoneyLeft(-$event->amount());
                break;
            case MoneyPrizeConvertedToBonus::class:
                /** @var MoneyPrizeConvertedToBonus $event */
                $this->changeMoneyLeft($event->moneyAmount());
                break;
            case ItemPrizeGenerated::class:
                /** @var ItemPrizeGenerated $event */
                $this->changeItemLeft($event->itemName(), -1);
                break;
            case ItemPrizeCanceled::class:
                /** @var ItemPrizeCanceled $event */
                $this->changeItemLeft($event->itemName(), 1);
                break;
            case MoneyPrizeCanceled::class:
                /** @var MoneyPrizeCanceled $event */
                $this->changeMoneyLeft($event->moneyAmount());
                break;
        }
    }
}