<?php

namespace App\Domain\Entity;

class MalePlayer extends Player
{
    public function __construct(
        string $name,
        int $skillLevel,
        private int $strength,  // 0-100
        private int $speed      // 0-100
    ) {
        parent::__construct($name, $skillLevel);
    }

    public function getTotalScore(): float
    {
        return $this->skillLevel * 0.5 + $this->strength * 0.3 + $this->speed * 0.2;
    }
}