<?php


namespace app\model;


use app\App;
use app\model\event\BonusesPrizeGenerated;
use app\model\event\ItemPrizeCanceled;
use app\model\event\ItemPrizeGenerated;
use app\model\event\MoneyPrizeCanceled;
use app\model\event\MoneyPrizeConvertedToBonus;
use app\model\event\MoneyPrizeGenerated;
use app\model\event\PrizeCanceled;
use app\model\event\PrizeGenerated;
use app\model\event\PrizeSent;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;

class Prize extends AggregateRoot
{

    public $email, $type, $details, $sent = false, $cancelled = false;

    protected function aggregateId(): string
    {
        return $this->email;
    }

    public static function newPrize(string $email, string $type, array $details, bool $sent = false): self
    {
        $payload = [
            'email' => $email,
            'type' => $type,
            'details' => $details,
            'sent' => $sent,
        ];
        $prize = new self();
        switch ($type) {
            case 'money':
                $event = MoneyPrizeGenerated::occur($email, $payload);
                break;
            case 'bonuses':
                $event = BonusesPrizeGenerated::occur($email, $payload);
                break;
            case 'item':
                $event = ItemPrizeGenerated::occur($email, $payload);
                break;
        }
        if (isset($event)) {
            $prize->recordThat($event);
            App::prizesConfiguration()->recordThat($event);
        }
        return $prize;
    }

    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'type' => $this->type,
            'details' => $this->email,
            'sent' => $this->sent,
            'cancelled' => $this->cancelled,
        ];
    }

    public function __toString(): string
    {
        return json_encode($this->toArray());
    }

    public function send()
    {
        if ($this->sent) {
            return;
        }
        $this->recordThat(PrizeSent::occur($this->email));
    }

    public function cancel()
    {
        if ($this->cancelled || $this->sent) {
            return;
        }
        $this->cancelled = true;
        switch ($this->type) {
            case 'money':
                $event = MoneyPrizeCanceled::occur($this->aggregateId(), ['moneyAmount' => $this->details['amount']]);
                break;
            case 'item':
                $event = ItemPrizeCanceled::occur($this->aggregateId(), ['itemName' => $this->details['itemName']]);
                break;
            default:
                $event = PrizeCanceled::occur($this->aggregateId());
                break;
        }
        $this->recordThat($event);
        App::prizesConfiguration()->recordThat($event);
    }

    public function convert2Bonuses()
    {
        if ($this->type !== 'money' || $this->sent) {
            return;
        }
        $bonusesAmount = $this->details['amount'] * App::prizesConfiguration()->money['bonusRate'];
        $event = MoneyPrizeConvertedToBonus::occur($this->email, [
            'moneyAmount' => $this->details['amount'],
            'bonusesAmount' => $bonusesAmount,
        ]);
        $this->recordThat($event);
        App::prizesConfiguration()->recordThat($event);
    }

    /**
     * @inheritDoc
     */
    protected function apply(AggregateChanged $event): void
    {
        if ($event instanceof PrizeGenerated) {
            $this->email = $event->email();
            $this->type = $event->type();
            $this->details = $event->details();
            return;
        }
        if ($event instanceof PrizeCanceled) {
            $this->cancelled = true;
            return;
        }
        switch (get_class($event)) {
            case MoneyPrizeConvertedToBonus::class:
                /** @var MoneyPrizeConvertedToBonus $event */
                $this->type = 'bonuses';
                $this->details['amount'] = $event->bonusesAmount();
                break;
            case PrizeSent::class:
                /** @var PrizeSent $event */
                $this->sent = true;
                break;
        }
    }
}