<?php

namespace App\Domain\Tournament;

use App\Domain\Entity\Player;
use App\Domain\Service\MaleMatchStrategy;

class MaleTournament extends AbstractTournament
{
    public function play(): Player
    {
        $strategy = new MaleMatchStrategy();
        $round = $this->players;

        while (count($round) > 1) {
            $nextRound = [];
            for ($i = 0; $i < count($round); $i += 2) {
                $nextRound[] = $strategy->playMatch($round[$i], $round[$i + 1]);
            }
            $round = $nextRound;
        }

        return $round[0];
    }
}