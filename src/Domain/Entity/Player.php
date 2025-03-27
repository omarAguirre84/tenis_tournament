<?php

namespace App\Domain\Entity;

abstract class Player
{
    public function __construct(
        protected string $name,
        protected int    $skillLevel // 0-100
    )
    {
    }

    abstract public function getTotalScore(): float;

    public function getName(): string
    {
        return $this->name;
    }
}