<?php

namespace app\prizeGenerators;

interface PrizeGeneratorInterface {

    public function isAvailable(): bool;

    public function getPrizeDetailsIfAvailable(): array;
}