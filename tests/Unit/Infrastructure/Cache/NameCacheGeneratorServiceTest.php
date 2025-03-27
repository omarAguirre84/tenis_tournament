<?php

namespace App\Tests\Unit\Infrastructure\Cache;

use App\Domain\Service\PlayerNameCacheManagerInterface;
use App\Domain\Service\RandomUserNameFetcherInterface;
use App\Infrastructure\Cache\NameCacheGeneratorService;
use PHPUnit\Framework\TestCase;

class NameCacheGeneratorServiceTest extends TestCase
{
    public function testReturnsNamesFromCacheOnly(): void
    {
        $gender = 'male';
        $count = 2;

        $mockCache = $this->createMock(PlayerNameCacheManagerInterface::class);
        $mockApi = $this->createMock(RandomUserNameFetcherInterface::class);

        $mockCache->expects($this->once())
            ->method('getFirstNames')
            ->with($gender, $count)
            ->willReturn(['John', 'Mike']);

        $mockCache->expects($this->once())
            ->method('getLastNames')
            ->with($gender, $count)
            ->willReturn(['Doe', 'Smith']);

        // API should not be called
        $mockApi->expects($this->never())->method('fetchNames');

        $service = new NameCacheGeneratorService($mockCache, $mockApi);
        $result = $service->getRandomNames($gender, $count);

        $this->assertCount(2, $result);
        $this->assertEquals('John', $result[0]['first']);
        $this->assertEquals('Doe', $result[0]['last']);
        $this->assertEquals('Mike', $result[1]['first']);
        $this->assertEquals('Smith', $result[1]['last']);
    }

    public function testFetchesFromApiWhenCacheIsInsufficient(): void
    {
        $gender = 'male';
        $count = 2;

        // se simula que el cache solo tiene 1 nombre disponible
        $mockCache = $this->createMock(PlayerNameCacheManagerInterface::class);
        $mockCache->method('getFirstNames')->willReturn(['John']);
        $mockCache->method('getLastNames')->willReturn(['Doe']);

        $apiResponse = [
            ['first' => 'Alex', 'last' => 'Smith'],
        ];

        $mockApi = $this->createMock(RandomUserNameFetcherInterface::class);
        $mockApi->expects($this->once())
            ->method('fetchNames')
            ->with($gender, 1)
            ->willReturn($apiResponse);

        // Espero que se almacenen en cache
        $mockCache->expects($this->once())
            ->method('storeNames')
            ->with($gender, $apiResponse);

        $generator = new NameCacheGeneratorService($mockCache, $mockApi);
        $result = $generator->getRandomNames($gender, $count);

        $this->assertCount($count, $result);
        $this->assertEquals(['John', 'Alex'], array_column($result, 'first'));
        $this->assertEquals(['Doe', 'Smith'], array_column($result, 'last'));
    }

    public function testFetchesAllFromApiWhenCacheIsEmpty(): void
    {
        $gender = 'female';
        $count = 3;

        $mockCache = $this->createMock(PlayerNameCacheManagerInterface::class);
        $mockCache->method('getFirstNames')->willReturn([]);
        $mockCache->method('getLastNames')->willReturn([]);

        $apiResponse = [
            ['first' => 'Alice', 'last' => 'Smith'],
            ['first' => 'Beth', 'last' => 'Johnson'],
            ['first' => 'Cara', 'last' => 'Williams'],
        ];

        $mockApi = $this->createMock(RandomUserNameFetcherInterface::class);
        $mockApi->expects($this->once())
            ->method('fetchNames')
            ->with($gender, $count)
            ->willReturn($apiResponse);

        $mockCache->expects($this->once())
            ->method('storeNames')
            ->with($gender, $apiResponse);

        $generator = new NameCacheGeneratorService($mockCache, $mockApi);
        $result = $generator->getRandomNames($gender, $count);

        $this->assertCount($count, $result);
        $this->assertEquals(['Alice', 'Beth', 'Cara'], array_column($result, 'first'));
        $this->assertEquals(['Smith', 'Johnson', 'Williams'], array_column($result, 'last'));
    }


}
