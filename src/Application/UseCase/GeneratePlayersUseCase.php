<?php

namespace App\Application\UseCase;

use App\Domain\Entity\Player;
use App\Domain\Enum\GenderEnum;
use App\Domain\Factory\PlayerFactory;
use App\Domain\Service\NameGeneratorServiceInterface;

class GeneratePlayersUseCase
{
    public function __construct(private NameGeneratorServiceInterface $nameGenerator)
    {
    }

    /**
     * @return Player[]
     */
    public function execute(GenderEnum $gender, int $count): array
    {
        $names = $this->nameGenerator->getRandomNames($gender->value, $count);

        $players = [];
        foreach ($names as $name) {
            $players[] = PlayerFactory::createFromName(
                $name['first'],
                $name['last'],
                $gender
            );
        }

        return $players;
    }
}
