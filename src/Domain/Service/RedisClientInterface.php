<?php

namespace App\Domain\Service;

interface RedisClientInterface
{
    public function lLen(string $key): int;
    public function lIndex(string $key, int $index): string|false;
    public function rPush(string $key, string $value): int;
    public function del(string ...$keys): int;
}
