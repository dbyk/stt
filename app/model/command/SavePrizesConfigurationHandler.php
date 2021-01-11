<?php

declare(strict_types=1);

namespace app\model\command;

use app\App;
use app\infrastructure\PrizesConfigurationRepository;

class SavePrizesConfigurationHandler
{
    private PrizesConfigurationRepository $repository;

    public function __construct(PrizesConfigurationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(SavePrizesConfiguration $setConfiguration): void
    {
        $pc = App::prizesConfiguration();
        if (is_null($pc)) {
            return;
        }
        $this->repository->save($pc);
    }
}