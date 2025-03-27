<?php

namespace App\Tests\Unit\Infrastructure\Http;

use App\Infrastructure\Http\RandomUserApiService;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class RandomUserApiServiceTest extends TestCase
{
    public function testFetchNamesParsesResponseCorrectly(): void
    {
        $expectedData = [
            'results' => [
                [
                    'name' => ['first' => 'Juan', 'last' => 'Pérez'],
                ],
                [
                    'name' => ['first' => 'Ana', 'last' => 'García'],
                ],
            ],
        ];

        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse->method('toArray')->willReturn($expectedData);

        $mockHttpClient = $this->createMock(HttpClientInterface::class);
        $mockHttpClient->method('request')->willReturn($mockResponse);

        $_ENV['API_NAMES_BASE_URL'] = 'https://dummy.api'; // necesario para evitar error

        $service = new RandomUserApiService($mockHttpClient);
        $result = $service->fetchNames('female', 2);

        $this->assertCount(2, $result);
        $this->assertEquals([
            ['first' => 'Juan', 'last' => 'Pérez'],
            ['first' => 'Ana', 'last' => 'García'],
        ], $result);
    }
}
