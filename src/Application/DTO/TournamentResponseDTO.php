<?php

namespace App\Application\DTO;

use App\Infrastructure\Doctrine\Entity\Tournament;

class TournamentResponseDTO
{
    public function __construct(public int    $id,
                                public string $playedAt,
                                public string $type)
    {
    }

    public static function fromEntity(Tournament $tournament): self
    {
        return new self(
            $tournament->getId(),
            $tournament->getPlayedAt()->format('Y-m-d H:i:s'),
            (new \ReflectionClass($tournament))->getShortName()
        );
    }
}