<?php

namespace App\Infrastructure\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: 'App\\Infrastructure\\Doctrine\\Repository\\TournamentRepository')]
#[ORM\Table(name: 'tournament')]
#[ORM\InheritanceType("SINGLE_TABLE")]
#[ORM\DiscriminatorColumn(name: "type", type: "string")]
#[ORM\DiscriminatorMap([
    "male" => MaleTournament::class,
    "female" => FemaleTournament::class,
])]
abstract class Tournament
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected ?int $id = null;

    #[ORM\Column(type: 'datetime')]
    protected \DateTimeInterface $playedAt;

    public function __construct()
    {
        $this->playedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayedAt(): \DateTimeInterface
    {
        return $this->playedAt;
    }

    public function setPlayedAt(\DateTimeInterface $playedAt): Tournament
    {
        $this->playedAt = $playedAt;
        return $this;
    }


}
