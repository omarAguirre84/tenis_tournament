<?php

namespace App\Tests\Unit\Domain\Tournament;

use App\Domain\Entity\MalePlayer;
use App\Domain\Tournament\MaleTournament;
use PHPUnit\Framework\TestCase;

class MaleTournamentTest extends TestCase
{
    public function testTournamentReturnsAMaleWinner(): void
    {
        $players = [
            new MalePlayer('P1', 90, 80, 70),
            new MalePlayer('P2', 85, 75, 65),
            new MalePlayer('P3', 88, 78, 68),
            new MalePlayer('P4', 82, 72, 62),
        ];

        $tournament = new MaleTournament($players);
        $winner = $tournament->play();

        $this->assertInstanceOf(MalePlayer::class, $winner);
    }

}
