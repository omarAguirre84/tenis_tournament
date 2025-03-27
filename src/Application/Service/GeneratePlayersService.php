<?php

namespace App\Application\Service;

use App\Domain\Entity\Player;
use App\Domain\Enum\GenderEnum;
use App\Domain\Factory\PlayerFactory;
use App\Domain\Service\NameGeneratorServiceInterface;

class GeneratePlayersService
{
    public function __construct(private NameGeneratorServiceInterface $nameGenerator)
    {
    }

    /**
     * @return Player[]
     */
    public function generate(int $count, GenderEnum $type): array
    {
        $gender = $type === GenderEnum::MALE ? 'male' : 'female';
        $names = $this->nameGenerator->getRandomNames($gender, $count);

        $players = [];
        foreach ($names as $i => $nameData) {
            $players[] = PlayerFactory::createFromName(
                $nameData['first'],
                $nameData['last'],
                $type,
            );
        }

        return $players;
    }
}
