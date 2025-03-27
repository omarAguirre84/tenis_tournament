<?php

namespace App\Infrastructure\Http;

use App\Domain\Service\RandomUserNameFetcherInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/** llamado a API externa */
class RandomUserApiService implements RandomUserNameFetcherInterface
{
    private string $base_url;

    public function __construct(private HttpClientInterface $httpClient)
    {
        $this->base_url = $_ENV['API_NAMES_BASE_URL'];
    }

    /**
     * @return array<int, array{first: string, last: string}>
     */
    public function fetchNames(string $gender, int $count): array
    {
        $response = $this->httpClient->request('GET', $this->base_url, [
            'query' => [
                'results' => $count,
                'gender'  => $gender,
                'inc'     => 'gender,name',
                'nat'     => 'ES',
                'noinfo'  => true
            ]
        ]);

        $data = $response->toArray();
        $results = $data['results'] ?? [];

        $array = array_map(fn($item) => [
            'first' => $item['name']['first'],
            'last'  => $item['name']['last'],
        ], $results);

        return $array;
    }
}