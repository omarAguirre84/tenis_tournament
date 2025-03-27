<?php

namespace App\Tests\Integration\UseCase;

use App\Application\UseCase\CleanPlayerCacheUseCase;
use App\Domain\Enum\GenderEnum;
use App\Domain\Service\PlayerNameCacheManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CleanPlayerCacheUseCaseIntegrationTest extends KernelTestCase
{
    private PlayerNameCacheManagerInterface $cacheManager;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->cacheManager = static::getContainer()->get(PlayerNameCacheManagerInterface::class);
    }

    public function testItClearsMaleNamesFromCache(): void
    {
        // load names into Redis
        $this->cacheManager->storeNames('male', [
            ['first' => 'John', 'last' => 'Doe'],
            ['first' => 'Mike', 'last' => 'Smith'],
        ]);

        $this->assertNotEmpty($this->cacheManager->getFirstNames('male', 10));
        $this->assertNotEmpty($this->cacheManager->getLastNames('male', 10));

        // Run use case
        $useCase = static::getContainer()->get(CleanPlayerCacheUseCase::class);
        $useCase->execute(GenderEnum::MALE);

        // Check Redis is cleared
        $this->assertEmpty($this->cacheManager->getFirstNames('male', 10));
        $this->assertEmpty($this->cacheManager->getLastNames('male', 10));
    }
}
