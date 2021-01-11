<?php

declare(strict_types=1);

namespace app\model\command;

use app\App;
use app\model\Prize;
use app\model\PrizeRepositoryInterface;

class CancelPrizeHandler
{
    private PrizeRepositoryInterface $repository;

    public function __construct(PrizeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(CancelPrize $command): void
    {
        $prize = $this->repository->get($command->email());
        if (!$prize) {
            return;
        }

        $prize->cancel();
        $this->repository->save($prize);
        App::$commandBus->dispatch(new SavePrizesConfiguration());
    }
}