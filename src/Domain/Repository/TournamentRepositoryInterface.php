<?php

namespace App\Domain\Repository;

use App\Domain\Enum\GenderEnum;

interface TournamentRepositoryInterface
{
    public function findByGender(GenderEnum $gender): array;

    public function findByDateRange(\DateTimeInterface $from, \DateTimeInterface $to): array;
}
