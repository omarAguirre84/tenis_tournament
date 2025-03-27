<?php

namespace App\Tests\Unit\Domain\Entity;

use App\Domain\Entity\FemalePlayer;
use PHPUnit\Framework\TestCase;

class FemalePlayerTest extends TestCase
{
    public function testGetTotalScore(): void
    {
        $player = new FemalePlayer("Anna", 90, 80);
        $expected = 90 * 0.7 + 80 * 0.3;
        $this->assertEquals($expected, $player->getTotalScore());
    }
}
