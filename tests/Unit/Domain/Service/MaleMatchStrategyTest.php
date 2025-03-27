<?php

namespace App\Tests\Unit\Domain\Service;

use App\Domain\Entity\MalePlayer;
use App\Domain\Service\MaleMatchStrategy;
use PHPUnit\Framework\TestCase;

class MaleMatchStrategyTest extends TestCase
{
    public function testPlayMatchReturnsOneOfThePlayers(): void
    {
        $player1 = new MalePlayer("A", 80, 70, 60);
        $player2 = new MalePlayer("B", 85, 65, 55);

        $strategy = new MaleMatchStrategy();
        $winner = $strategy->playMatch($player1, $player2);

        $this->assertContains($winner, [$player1, $player2]);
    }
}
