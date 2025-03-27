<?php

namespace App\Infrastructure\Cache;

use App\Domain\Service\NameGeneratorServiceInterface;
use App\Domain\Service\PlayerNameCacheManagerInterface;
use App\Domain\Service\RandomUserNameFetcherInterface;


/**
 * Gestiona nombres, logica de negocio para entregar nombres validos
 */
class NameCacheGeneratorService implements NameGeneratorServiceInterface
{
    public function __construct(private PlayerNameCacheManagerInterface $cacheManager,
                                private RandomUserNameFetcherInterface  $api)
    {
    }

    public function getRandomNames(string $gender, int $count): array
    {
        $firstNames = $this->cacheManager->getFirstNames($gender, $count);
        $lastNames = $this->cacheManager->getLastNames($gender, $count);
//        dd($firstNames, $lastNames);

        //verifico si hay suficientes, sino api call
        if (count($firstNames) < $count || count($lastNames) < $count) {

            $needed = max($count - count($firstNames), $count - count($lastNames));
            $fetched = $this->api->fetchNames($gender, $needed);
            $this->cacheManager->storeNames($gender, $fetched);

            $firstNames = array_merge($firstNames, array_column($fetched, 'first'));
            $lastNames = array_merge($lastNames, array_column($fetched, 'last'));
        }

//        shuffle($firstNames);
//        shuffle($lastNames);

        $names = [];
        for ($i = 0; $i < $count; $i++) {
            $names[] = [
                'first' => $firstNames[$i],
                'last'  => $lastNames[$i],
            ];
        }

        return $names;
    }
}
