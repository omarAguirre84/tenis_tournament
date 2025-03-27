<?php

namespace App\Tests\Integration\Cache;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Infrastructure\Cache\NameCacheGeneratorService;
use App\Domain\Service\PlayerNameCacheManagerInterface;
use App\Domain\Service\RandomUserNameFetcherInterface;
use App\Domain\Enum\GenderEnum;
use PHPUnit\Framework\MockObject\MockObject;

class NameCacheGeneratorServiceIntegrationTest extends KernelTestCase
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

    public function testItGeneratesNamesFromCacheOrApi()
    {
        self::bootKernel();
        $container = static::getContainer();

        /** @var PlayerNameCacheManagerInterface|MockObject $cacheManager */
        $cacheManager = $container->get(PlayerNameCacheManagerInterface::class);
        /** @var RandomUserNameFetcherInterface|MockObject $randomUserNameFetcher */
        $randomUserNameFetcher = $this->createMock(RandomUserNameFetcherInterface::class);

        // Mock de fetchNames
        $randomUserNameFetcher->expects($this->once())
        ->method('fetchNames')
            ->with(GenderEnum::MALE->value, 5)
            ->willReturn([
                ['first' => 'John', 'last' => 'Doe'],
                ['first' => 'Jane', 'last' => 'Smith'],
                ['first' => 'Alex', 'last' => 'Brown'],
                ['first' => 'Chris', 'last' => 'Davis'],
                ['first' => 'Ryan', 'last' => 'Miller'],
            ]);

        $nameCacheGeneratorService = new NameCacheGeneratorService($cacheManager, $randomUserNameFetcher);

        $cacheManager->clearNames(GenderEnum::MALE->value);

        $names = $nameCacheGeneratorService->getRandomNames(GenderEnum::MALE->value, 5);

        // Verificamos si nombres se generaron correctamente
        $this->assertCount(5, $names);
        $this->assertArrayHasKey('first', $names[0]);
        $this->assertArrayHasKey('last', $names[0]);

        // Verificamos que los nombres fueron almacenados en cache
        $firstNamesInCache = $cacheManager->getFirstNames(GenderEnum::MALE->value, 5);
        $lastNamesInCache = $cacheManager->getLastNames(GenderEnum::MALE->value, 5);

        $this->assertCount(5, $firstNamesInCache);
        $this->assertCount(5, $lastNamesInCache);
    }
}