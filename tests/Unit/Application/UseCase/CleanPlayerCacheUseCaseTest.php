<?php

namespace App\Tests\Unit\Application\UseCase;

use App\Application\UseCase\CleanPlayerCacheUseCase;
use App\Domain\Enum\GenderEnum;
use App\Domain\Service\PlayerNameCacheManagerInterface;
use PHPUnit\Framework\TestCase;

class CleanPlayerCacheUseCaseTest extends TestCase
{
    public function testItClearsCacheByGender(): void
    {
        $cacheMock = $this->createMock(PlayerNameCacheManagerInterface::class);
        $cacheMock->expects($this->once())
            ->method('clearNames')
            ->with('male');

        $useCase = new CleanPlayerCacheUseCase($cacheMock);
        $useCase->execute(GenderEnum::MALE);
    }
}
