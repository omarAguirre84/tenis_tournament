<?php

namespace App\Domain\Service;

interface PlayerNameCacheManagerInterface
{
    public function clearNames(string $gender): void;

    public function getFirstNames(string $gender, int $count): array;

    public function getLastNames(string $gender, int $count): array;

    public function storeNames(string $gender, array $names): void;
}
