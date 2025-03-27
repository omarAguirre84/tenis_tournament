<?php

namespace App\Tests\Unit\Domain\Factory;

use App\Domain\Entity\FemalePlayer;
use App\Domain\Entity\MalePlayer;
use App\Domain\Enum\GenderEnum;
use App\Domain\Factory\PlayerFactory;
use PHPUnit\Framework\TestCase;

class PlayerFactoryTest extends TestCase
{
    public function testCreateMalePlayer(): void
    {
        $player = PlayerFactory::createFromName('John', 'Doe', GenderEnum::MALE);
        $this->assertInstanceOf(MalePlayer::class, $player);
        $this->assertStringContainsString('John Doe', $player->getName());
    }

    public function testCreateFemalePlayer(): void
    {
        $player = PlayerFactory::createFromName('Jane', 'Smith', GenderEnum::FEMALE);
        $this->assertInstanceOf(FemalePlayer::class, $player);
        $this->assertStringContainsString('Jane Smith', $player->getName());
    }
}
