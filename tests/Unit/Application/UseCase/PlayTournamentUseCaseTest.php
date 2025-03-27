<?php

namespace App\Tests\Unit\Application\UseCase;

use App\Application\Service\GeneratePlayersService;
use App\Application\UseCase\PlayTournamentUseCase;
use App\Domain\Entity\FemalePlayer;
use App\Domain\Entity\MalePlayer;
use App\Domain\Enum\GenderEnum;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class PlayTournamentUseCaseTest extends TestCase
{
    public function testItReturnsAWinnerForMaleTournament(): void
    {
        $count = 4;
        $type = GenderEnum::MALE;

        // Jugadores male
        $players = [
            new MalePlayer('A', 50, 50, 50),
            new MalePlayer('B', 60, 60, 60),
            new MalePlayer('C', 70, 70, 70),
            new MalePlayer('D', 80, 80, 80),
        ];

        // Mock de GeneratePlayersService
        $generatePlayersService = $this->createMock(GeneratePlayersService::class);
        $generatePlayersService->expects($this->once())
            ->method('generate')
            ->with($count, $type)
            ->willReturn($players);

        // Mock deEntityManager
        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->once())->method('persist');
        $em->expects($this->once())->method('flush');

        // Use case
        $useCase = new PlayTournamentUseCase($em, $generatePlayersService);
        $winner = $useCase->execute($type, $count);

        $this->assertInstanceOf(MalePlayer::class, $winner);
        $this->assertContains($winner->getName(), array_map(fn($p) => $p->getName(), $players));
    }

    public function testItReturnsAWinnerForFemaleTournament(): void
    {
        $count = 4;
        $type = GenderEnum::FEMALE;

        $players = [];
        for ($i = 0; $i < $count; $i++) {
            $players[] = new FemalePlayer("Player $i", rand(50, 100), rand(50, 100));
        }

        $generatePlayersService = $this->createMock(GeneratePlayersService::class);
        $generatePlayersService->expects($this->once())
            ->method('generate')
            ->with($count, $type)
            ->willReturn($players);

        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->once())->method('persist');
        $em->expects($this->once())->method('flush');

        $useCase = new PlayTournamentUseCase($em, $generatePlayersService);
        $winner = $useCase->execute($type, $count);

        $this->assertInstanceOf(FemalePlayer::class, $winner);
        $this->assertContains($winner->getName(), array_map(fn($p) => $p->getName(), $players));
    }


    public function testItThrowsExceptionIfCountIsNotPowerOfTwo(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $generatePlayersService = $this->createMock(GeneratePlayersService::class);
        $em = $this->createMock(EntityManagerInterface::class);

        $useCase = new PlayTournamentUseCase($em, $generatePlayersService);
        $useCase->execute(GenderEnum::MALE, 3); // No es potencia de 2
    }
}
