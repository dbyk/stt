<?php

declare(strict_types=1);

namespace app\model\command;

use app\App;
use app\model\Prize;
use app\model\PrizeRepositoryInterface;

class RegisterPrizeHandler
{
    private PrizeRepositoryInterface $repository;

    public function __construct(PrizeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(RegisterPrize $command): void
    {
        if ($this->repository->get($command->email())) {
            return;
        }
        $prize = Prize::newPrize($command->email(), $command->type(), $command->details());
        $this->repository->save($prize);
        App::$commandBus->dispatch(new SavePrizesConfiguration());
    }
}