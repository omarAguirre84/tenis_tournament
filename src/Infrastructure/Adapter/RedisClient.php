<?php

namespace App\Infrastructure\Adapter;

use App\Domain\Service\RedisClientInterface;

class RedisClient implements RedisClientInterface
{
    public function __construct(private \Redis|\Predis\Client $redis) {}

    public function lLen(string $key): int
    {
        return $this->redis->lLen($key);
    }

    public function lIndex(string $key, int $index): string|false
    {
        return $this->redis->lIndex($key, $index);
    }

    public function rPush(string $key, string $value): int
    {
        $result = $this->redis->rPush($key, $value);

        if ($result === false) {
            throw new \RuntimeException("Failed to push value to Redis.");
        }

        return $result;
    }

    public function del(string ...$keys): int
    {
        return $this->redis->del(...$keys);
    }
}
