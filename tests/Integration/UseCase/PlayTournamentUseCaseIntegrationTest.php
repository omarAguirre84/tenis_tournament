<?php

namespace App\Tests\Integration\UseCase;

use App\Domain\Entity\FemalePlayer;
use App\Domain\Entity\MalePlayer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Application\UseCase\PlayTournamentUseCase;
use App\Domain\Enum\GenderEnum;

class PlayTournamentUseCaseIntegrationTest extends KernelTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->executeMigrations();
    }

    private function executeMigrations(): void
    {
        $command = 'php bin/console doctrine:migrations:migrate --env=test --no-interaction';
        shell_exec($command);
    }

    public function testItReturnsValidResponseForMaleTournament()
    {
        self::bootKernel();
        $container = static::getContainer();

        /** @var PlayTournamentUseCase $useCase */
        $useCase = $container->get(PlayTournamentUseCase::class);

        $response = $useCase->execute(GenderEnum::MALE, 8);

        $this->assertInstanceOf(MalePlayer::class, $response);
        $this->assertNotEmpty($response->getName());
    }

    public function testItReturnsValidResponseForFemaleTournament()
    {
        self::bootKernel();
        $container = static::getContainer();

        /** @var PlayTournamentUseCase $useCase */
        $useCase = $container->get(PlayTournamentUseCase::class);

        $response = $useCase->execute(GenderEnum::FEMALE, 8);

        $this->assertInstanceOf(FemalePlayer::class, $response);
        $this->assertNotEmpty($response->getName());
    }
}
