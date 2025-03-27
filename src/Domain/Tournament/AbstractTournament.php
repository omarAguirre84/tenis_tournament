<?php

namespace App\Domain\Tournament;

use App\Domain\Entity\Player;

abstract class AbstractTournament
{
    /** @var Player[] */
    protected array $players;

    public function __construct(array $players)
    {
        $this->players = $players;
    }

    abstract public function play(): Player;
}