<?php

namespace App\Tests\Integration\Http;

use App\Domain\Service\RandomUserNameFetcherInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use App\Domain\Enum\GenderEnum;

class RandomUserApiServiceTest extends TestCase
{
    public function testFetchNamesReturnsParsedNames()
    {
        $mockResponse = new MockResponse(json_encode([
            'results' => [
                ['name' => ['first' => 'John', 'last' => 'Doe']],
                ['name' => ['first' => 'Jane', 'last' => 'Smith']],
            ]
        ]));

        $httpClientMock = new MockHttpClient($mockResponse);

        // Usar la interfaz en lugar de la clase concreta
        $apiService = $this->createMock(RandomUserNameFetcherInterface::class);
        $apiService->method('fetchNames')->willReturn([
            ['first' => 'John', 'last' => 'Doe'],
            ['first' => 'Jane', 'last' => 'Smith']
        ]);

        $names = $apiService->fetchNames(GenderEnum::MALE->value, 2);

        $this->assertCount(2, $names);
        $this->assertEquals('John', $names[0]['first']);
        $this->assertEquals('Doe', $names[0]['last']);
    }

    public function testFetchNamesHandlesEmptyResponse()
    {
        $mockResponse = new MockResponse(json_encode(['results' => []]));

        $httpClientMock = new MockHttpClient($mockResponse);

        // Usar la interfaz para el mock
        $apiService = $this->createMock(RandomUserNameFetcherInterface::class);
        $apiService->method('fetchNames')->willReturn([]);

        $names = $apiService->fetchNames(GenderEnum::MALE->value, 2);

        $this->assertCount(0, $names);
    }

    public function testFetchNamesHandlesApiError()
    {
        $httpClientMock = new MockHttpClient(function () {
            throw new \Exception('API error');
        });

        // Usar la interfaz para el mock
        $apiService = $this->createMock(RandomUserNameFetcherInterface::class);
        $apiService->method('fetchNames')->will($this->throwException(new \Exception('API error')));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('API error');

        $apiService->fetchNames(GenderEnum::MALE->value, 2);
    }
}