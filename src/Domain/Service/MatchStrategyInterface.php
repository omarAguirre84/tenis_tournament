<?php

namespace App\Domain\Service;

use App\Domain\Entity\Player;

interface MatchStrategyInterface
{
    public function playMatch(Player $player1, Player $player2): Player;
}