<?php

namespace App\Tests\Unit\Domain\Service;

use App\Domain\Entity\FemalePlayer;
use App\Domain\Service\FemaleMatchStrategy;
use PHPUnit\Framework\TestCase;

class FemaleMatchStrategyTest extends TestCase
{
    public function testPlayMatchReturnsOneOfThePlayers(): void
    {
        $player1 = new FemalePlayer("X", 85, 90);
        $player2 = new FemalePlayer("Y", 80, 95);

        $strategy = new FemaleMatchStrategy();
        $winner = $strategy->playMatch($player1, $player2);

        $this->assertContains($winner, [$player1, $player2]);
    }
}
