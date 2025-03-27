<?php

namespace App\Domain\Service;

interface RandomUserNameFetcherInterface
{
    /**
     * @return array<int, array{first: string, last: string}>
     */
    public function fetchNames(string $gender, int $count): array;
}
