<?php

namespace App\Tests\Unit\Domain\Entity;

use App\Domain\Entity\MalePlayer;
use PHPUnit\Framework\TestCase;

class MalePlayerTest extends TestCase
{
    public function testGetTotalScore(): void
    {
        $player = new MalePlayer("John", 80, 70, 60);
        $expected = 80 * 0.5 + 70 * 0.3 + 60 * 0.2;
        $this->assertEquals($expected, $player->getTotalScore());
    }
}

