<?php

namespace App\Tests\Integration\UseCase;

use App\Application\UseCase\GeneratePlayersUseCase;
use App\Application\UseCase\CleanPlayerCacheUseCase;
use App\Domain\Enum\GenderEnum;
use App\Domain\Entity\Player;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GeneratePlayersUseCaseIntegrationTest extends KernelTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->executeMigrations();
    }

    private function executeMigrations(): void
    {
        shell_exec('php bin/console doctrine:migrations:migrate --env=test --no-interaction');
    }

    public function testItGeneratesTheCorrectNumberOfPlayersWithValidNames(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        /** @var CleanPlayerCacheUseCase $cleaner */
        $cleaner = $container->get(CleanPlayerCacheUseCase::class);
        $cleaner->execute(GenderEnum::MALE);

        /** @var GeneratePlayersUseCase $useCase */
        $useCase = $container->get(GeneratePlayersUseCase::class);

        $players = $useCase->execute(GenderEnum::MALE, 8);

        $this->assertCount(8, $players);

        foreach ($players as $player) {
            $this->assertInstanceOf(Player::class, $player);
            $this->assertNotEmpty($player->getName(), 'Player name should not be empty');
        }
    }
}
