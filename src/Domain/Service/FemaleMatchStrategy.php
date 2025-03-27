<?php

namespace App\Domain\Service;

use App\Domain\Entity\Player;

class FemaleMatchStrategy implements MatchStrategyInterface
{
    public function playMatch(Player $player1, Player $player2): Player
    {
        $luck1 = rand(0, 10);
        $luck2 = rand(0, 10);

        $score1 = $player1->getTotalScore() + $luck1;
        $score2 = $player2->getTotalScore() + $luck2;

        return $score1 >= $score2 ? $player1 : $player2;
    }
}