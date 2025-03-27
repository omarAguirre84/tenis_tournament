<?php

namespace App\Application\UseCase;

use App\Domain\Repository\TournamentRepositoryInterface;
use App\Domain\Utils\DateRangeValidator;

class FindTournamentsByDateRangeUseCase
{
    public function __construct(private TournamentRepositoryInterface $repository) {}

    public function execute(\DateTimeInterface $from, \DateTimeInterface $to): array
    {
        DateRangeValidator::validate($from, $to);

        return $this->repository->findByDateRange($from, $to);
    }
}