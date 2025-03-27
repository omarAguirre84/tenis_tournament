<?php

namespace App\Tests\Unit\Infrastructure\Service;

use App\Domain\Service\RedisClientInterface;
use App\Infrastructure\Service\NameCacheService;
use PHPUnit\Framework\TestCase;

class NameCacheServiceTest extends TestCase
{
    public function testGetFirstNamesReturnsExpectedFromRedis(): void
    {
        $mockRedis = $this->createMock(RedisClientInterface::class);

        $mockRedis->method('lLen')->willReturn(2);
        $mockRedis->method('lIndex')
            ->willReturnOnConsecutiveCalls('Alice', 'Laura');

        $service = new NameCacheService($mockRedis);
        $result = $service->getFirstNames('female', 2);

        $this->assertEquals(['Alice', 'Laura'], $result);
    }

    public function testStoreNamesPushesToRedis(): void
    {
        $mockRedis = $this->createMock(RedisClientInterface::class);
        $mockRedis->expects($this->exactly(2))
            ->method('rPush')
            ->withConsecutive(
                ['names:male:first', 'John'],
                ['names:male:last', 'Smith']
            );

        $service = new NameCacheService($mockRedis);
        $service->storeNames('male', [['first' => 'John', 'last' => 'Smith']]);

        $this->assertTrue(true); // dummy assert to mark test passed
    }

    public function testClearNamesDeletesKeys(): void
    {
        $mockRedis = $this->createMock(RedisClientInterface::class);
        $mockRedis->expects($this->once())
            ->method('del')
            ->with('names:female:first', 'names:female:last');

        $service = new NameCacheService($mockRedis);
        $service->clearNames('female');

        $this->assertTrue(true);
    }
}
