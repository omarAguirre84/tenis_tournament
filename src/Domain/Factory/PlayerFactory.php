<?php

namespace App\Domain\Factory;

use App\Domain\Entity\FemalePlayer;
use App\Domain\Entity\MalePlayer;
use App\Domain\Entity\Player;
use App\Domain\Enum\GenderEnum;

class PlayerFactory
{
    public static function createFromName(string $firstName, string $lastName, GenderEnum $type): Player
    {
        $name = "$firstName $lastName";
        $skill = rand(50, 100);

        return match ($type) {
            GenderEnum::MALE => new MalePlayer(
                $name,
                $skill,
                rand(50, 100), // strength
                rand(50, 100)  // speed
            ),
            GenderEnum::FEMALE => new FemalePlayer(
                $name,
                $skill,
                rand(50, 100)   // reaction
            ),
        };
    }


    /**
     * @return Player[]
     */
    public static function createRandomPlayers(int $count, GenderEnum $type): array
    {
        $players = [];

        for ($i = 1; $i <= $count; $i++) {
            $name = "{$type->value}_Player_$i";
            $skill = rand(50, 100);

            if ($type === GenderEnum::MALE) {
                $players[] = new MalePlayer($name, $skill, rand(50, 100), rand(50, 100));
            } else {
                $players[] = new FemalePlayer($name, $skill, rand(50, 100));
            }
        }

        return $players;
    }
}
