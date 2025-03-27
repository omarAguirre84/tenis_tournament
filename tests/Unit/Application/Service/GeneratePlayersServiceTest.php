<?php

namespace App\Tests\Unit\Application\Service;

use App\Application\Service\GeneratePlayersService;
use App\Domain\Enum\GenderEnum;
use App\Domain\Service\NameGeneratorServiceInterface;
use PHPUnit\Framework\TestCase;

class GeneratePlayersServiceTest extends TestCase
{
    public function testGeneratesCorrectNumberOfMalePlayers(): void
    {
        $mockGenerator = $this->createMock(NameGeneratorServiceInterface::class);
        $mockGenerator->method('getRandomNames')
            ->willReturn([
                ['first' => 'John', 'last' => 'Doe'],
                ['first' => 'Mike', 'last' => 'Smith'],
            ]);

        $service = new GeneratePlayersService($mockGenerator);
        $players = $service->generate(2, GenderEnum::MALE);

        $this->assertCount(2, $players);
        $this->assertEquals('John Doe', $players[0]->getName());
        $this->assertEquals('Mike Smith', $players[1]->getName());
    }

    public function testGeneratesCorrectNumberOfFemalePlayers(): void
    {
        $mockGenerator = $this->createMock(NameGeneratorServiceInterface::class);
        $mockGenerator->method('getRandomNames')
            ->willReturn([
                ['first' => 'Anna', 'last' => 'Taylor'],
                ['first' => 'Eva', 'last' => 'Brown'],
            ]);

        $service = new GeneratePlayersService($mockGenerator);
        $players = $service->generate(2, GenderEnum::FEMALE);

        $this->assertCount(2, $players);
        $this->assertEquals('Anna Taylor', $players[0]->getName());
        $this->assertEquals('Eva Brown', $players[1]->getName());
    }

}
