<?php


namespace app\prizeGenerators;


use app\App;
use app\model\command\RegisterPrize;
use app\model\PrizesConfiguration;

class PrizeGenerator
{
    private PrizesConfiguration $pc;

    public function __construct(PrizesConfiguration $pc)
    {
        $this->pc = $pc;
    }

    /**
     * Generates a prize & call a RegisterPrize command
     *
     * @param string $email
     * @param string|null $type
     * @return void
     */
    public function generate(string $email, string $type = null): void
    {
        $types = isset($type) ? [$type] : ['money', 'bonuses', 'item'];
        $prizeData = $this->generateAvailablePrizeData($types);
        if (is_null($prizeData)) {
            return;
        }
        $prizeData += [
            'email' => $email,
        ];
        App::$commandBus->dispatch(new RegisterPrize($prizeData));
    }

    private function generateAvailablePrizeData(array $available): ?array
    {
        do {
            $i = random_int(0, count($available) - 1);
            $prizeData = $this->getPrizeDataIfAvailable($available[$i]);
            // if null, exclude selected type of prize
            unset($available[$i]);
        } while (is_null($prizeData) && count($available) > 0);
        return $prizeData;
    }

    /**
     * @param string $type
     * @return PrizeGeneratorInterface|null null if prize type is unavailable
     */
    private function getPrizeDataIfAvailable(string $type): ?array
    {
        // @todo convert to `match` if PHP8
        switch ($type) {
            case 'money':
                $generator = new MoneyPrizeGenerator($this->pc->money);
                break;
            case 'bonuses':
                $generator = new BonusesPrizeGenerator($this->pc->bonuses);
                break;
            case 'item':
                $generator = new ItemPrizeGenerator($this->pc->item);
                break;
        }
        if (!isset($generator)) {
            return null;
        }
        /** @var PrizeGeneratorInterface $generator */
        if (!$generator->isAvailable()) {
            return null;
        }
        return [
            'type' => $type,
            'details' => $generator->getPrizeDetailsIfAvailable()
        ];
    }

}