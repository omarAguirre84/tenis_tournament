<?php

namespace App\Tests\Integration\UseCase;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Application\UseCase\CleanPlayerCacheUseCase;
use App\Application\UseCase\GeneratePlayersUseCase;
use App\Domain\Enum\GenderEnum;
use App\Domain\Service\PlayerNameCacheManagerInterface;

class CleanPlayerCacheUseCaseAndGeneratePlayersUseCaseIntegrationTest extends KernelTestCase
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

    public function testCacheIsClearedAndPlayersAreGenerated()
    {
        self::bootKernel();
        $container = static::getContainer();

        /** @var CleanPlayerCacheUseCase $cleanPlayerCacheUseCase */
        $cleanPlayerCacheUseCase = $container->get(CleanPlayerCacheUseCase::class);
        /** @var GeneratePlayersUseCase $generatePlayersUseCase */
        $generatePlayersUseCase = $container->get(GeneratePlayersUseCase::class);

        $cleanPlayerCacheUseCase->execute(GenderEnum::MALE);

        // Verificar cache vacio
        $cacheManager = $container->get(PlayerNameCacheManagerInterface::class);
        $firstNames = $cacheManager->getFirstNames(GenderEnum::MALE->value, 5);
        $this->assertEmpty($firstNames, 'Cache is not cleared.');

        // Generar nuevos players
        $generatePlayersUseCase->execute(GenderEnum::MALE, 8);

        // Verificar cache con contenido
        $firstNamesAfterGeneration = $cacheManager->getFirstNames(GenderEnum::MALE->value, 5);
        $this->assertNotEmpty($firstNamesAfterGeneration, 'Players were not generated properly.');
    }
}
