<?php

namespace App\Application\UseCase;

use App\Domain\Enum\GenderEnum;
use App\Domain\Repository\TournamentRepositoryInterface;

class FindTournamentsByGenderUseCase
{
    public function __construct(private TournamentRepositoryInterface $repository) {}

    public function execute(GenderEnum $gender): array
    {
        return $this->repository->findByGender($gender);
    }
}
