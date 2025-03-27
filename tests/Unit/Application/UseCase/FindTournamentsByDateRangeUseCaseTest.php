<?php

namespace App\Tests\Unit\Application\UseCase;

use App\Application\UseCase\FindTournamentsByDateRangeUseCase;
use App\Domain\Repository\TournamentRepositoryInterface;
use PHPUnit\Framework\TestCase;

class FindTournamentsByDateRangeUseCaseTest extends TestCase
{
    public function testItReturnsResultsFromRepositoryByDateRange(): void
    {
        $from = new \DateTimeImmutable('2025-01-01');
        $to = new \DateTimeImmutable('2025-01-31');

        $mockRepository = $this->createMock(TournamentRepositoryInterface::class);
        $mockRepository->expects($this->once())
            ->method('findByDateRange')
            ->with($from, $to)
            ->willReturn(['tournament1', 'tournament2']);

        $useCase = new FindTournamentsByDateRangeUseCase($mockRepository);
        $result = $useCase->execute($from, $to);

        $this->assertCount(2, $result);
        $this->assertSame(['tournament1', 'tournament2'], $result);
    }
}
