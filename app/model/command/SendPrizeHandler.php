<?php

declare(strict_types=1);

namespace app\model\command;

use app\App;
use app\model\Prize;
use app\model\PrizeRepositoryInterface;

class SendPrizeHandler
{
    private PrizeRepositoryInterface $repository;

    public function __construct(PrizeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(RegisterPrize $command): void
    {
        /** @var Prize $prize */
        $prize = $this->repository->get($command->email());
        if (!$prize) {
            return;
        }
        $prize->send();
        $this->repository->save($prize);
    }
}