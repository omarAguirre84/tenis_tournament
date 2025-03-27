<?php

namespace App\Tests\Unit\Domain\Tournament;

use App\Domain\Entity\FemalePlayer;
use App\Domain\Tournament\FemaleTournament;
use PHPUnit\Framework\TestCase;

class FemaleTournamentTest extends TestCase
{
    public function testTournamentReturnsAFemaleWinner(): void
    {
        $players = [
            new FemalePlayer('P1', 90, 90),
            new FemalePlayer('P2', 80, 85),
            new FemalePlayer('P3', 88, 82),
            new FemalePlayer('P4', 84, 80),
        ];

        $tournament = new FemaleTournament($players);
        $winner = $tournament->play();

        $this->assertInstanceOf(FemalePlayer::class, $winner);
    }

}
