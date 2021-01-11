<?php

declare(strict_types=1);

namespace app\model;

interface PrizeRepositoryInterface
{
    public function save(Prize $prize): void;
    public function get(string $id): ?Prize;
}