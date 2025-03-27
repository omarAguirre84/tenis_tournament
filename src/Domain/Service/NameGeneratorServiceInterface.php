<?php

namespace App\Domain\Service;

interface NameGeneratorServiceInterface
{
    /**
     * @return array<int, array{first: string, last: string}>
     */
    public function getRandomNames(string $gender, int $count): array;
}
