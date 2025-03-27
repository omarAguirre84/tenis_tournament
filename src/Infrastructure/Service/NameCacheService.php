<?php

namespace App\Infrastructure\Service;

use App\Domain\Service\PlayerNameCacheManagerInterface;
use App\Domain\Service\RedisClientInterface;

class NameCacheService implements PlayerNameCacheManagerInterface
{
    public function __construct(private RedisClientInterface $redis) {}

    private function getKey(string $gender, string $type): string
    {
        return "names:{$gender}:{$type}";
    }

    public function getFirstNames(string $gender, int $count): array
    {
        return $this->readMany($this->getKey($gender, 'first'), $count);
    }

    public function getLastNames(string $gender, int $count): array
    {
        return $this->readMany($this->getKey($gender, 'last'), $count);
    }

    public function storeNames(string $gender, array $names): void
    {
        $firsts = array_column($names, 'first');
        $lasts = array_column($names, 'last');

        $this->pushMany($this->getKey($gender, 'first'), $firsts);
        $this->pushMany($this->getKey($gender, 'last'), $lasts);
    }

    private function readMany(string $key, int $count): array
    {
        $length = $this->redis->lLen($key);
        if ($length === 0) {
            return [];
        }

        $items = [];
        for ($i = 0; $i < min($count, $length); $i++) {
            $value = $this->redis->lIndex($key, $i);
            if ($value !== false) {
                $items[] = $value;
            }
        }
        return $items;
    }

    private function pushMany(string $key, array $values): void
    {
        foreach ($values as $value) {
            $this->redis->rPush($key, $value);
        }
    }

    //$nameCacheService->clearNames('male');
    //$nameCacheService->clearNames('female');
    public function clearNames(string $gender): void
    {
        $this->redis->del(
            $this->getKey($gender, 'first'),
            $this->getKey($gender, 'last')
        );
    }
}
