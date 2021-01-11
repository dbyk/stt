<?php

declare(strict_types=1);

namespace app\model\command;

use app\App;
use app\infrastructure\PrizesConfigurationRepository;
use app\model\PrizesConfiguration;

class SetPrizesConfigurationHandler
{
    private PrizesConfigurationRepository $repository;

    public function __construct(PrizesConfigurationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(SetPrizesConfiguration $setConfiguration): void
    {
        $pc = App::$prizesConfigurationRepository->get('1');
        if (is_null($pc)) {
            $pc = PrizesConfiguration::init();
        }
        $pc->updateData($setConfiguration->money(), $setConfiguration->bonuses(), $setConfiguration->item());
        $this->repository->save($pc);
    }
}