<?php

namespace App\Domain\Entity;

class FemalePlayer extends Player
{
    public function __construct(
        string      $name,
        int         $skillLevel,
        private int $reactionTime // 0-100
    )
    {
        parent::__construct($name, $skillLevel);
    }

    public function getTotalScore(): float
    {
        return $this->skillLevel * 0.7 + $this->reactionTime * 0.3;
    }
}