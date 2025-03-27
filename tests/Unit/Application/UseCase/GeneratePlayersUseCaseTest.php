<?php

namespace App\Tests\Unit\Application\UseCase;

use App\Application\UseCase\GeneratePlayersUseCase;
use App\Domain\Entity\Player;
use App\Domain\Enum\GenderEnum;
use App\Domain\Service\NameGeneratorServiceInterface;
use PHPUnit\Framework\TestCase;

class GeneratePlayersUseCaseTest extends TestCase
{
    public function testItGeneratesExpectedPlayers(): void
    {
        $mockNameService = $this->createMock(NameGeneratorServiceInterface::class);
        $mockNameService->expects($this->once())
            ->method('getRandomNames')
            ->with('female', 2)
            ->willReturn([
                ['first' => 'Anna', 'last' => 'Smith'],
                ['first' => 'Maria', 'last' => 'Lopez'],
            ]);

        $useCase = new GeneratePlayersUseCase($mockNameService);
        $players = $useCase->execute(GenderEnum::FEMALE, 2);

        $this->assertCount(2, $players);
        $this->assertContainsOnlyInstancesOf(Player::class, $players);
    }
}
