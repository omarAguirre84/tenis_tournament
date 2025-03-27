<?php

namespace App\Tests\Unit\Application\UseCase;

use App\Application\UseCase\FindTournamentsByGenderUseCase;
use App\Domain\Enum\GenderEnum;
use App\Domain\Repository\TournamentRepositoryInterface;
use PHPUnit\Framework\TestCase;

class FindTournamentsByGenderUseCaseTest extends TestCase
{
    public function testItFindsTournamentsByGender(): void
    {
        $mockRepo = $this->createMock(TournamentRepositoryInterface::class);
        $mockRepo->expects($this->once())
            ->method('findByGender')
            ->with(GenderEnum::FEMALE)
            ->willReturn(['female1', 'female2']);

        $useCase = new FindTournamentsByGenderUseCase($mockRepo);
        $result = $useCase->execute(GenderEnum::FEMALE);

        $this->assertSame(['female1', 'female2'], $result);
    }
}
