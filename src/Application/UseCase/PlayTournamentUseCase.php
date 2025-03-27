<?php

namespace App\Application\UseCase;

use App\Application\Service\GeneratePlayersService;
use App\Domain\Entity\Player;
use App\Domain\Enum\GenderEnum;
use App\Domain\Tournament\FemaleTournament;
use App\Domain\Tournament\MaleTournament;
use App\Domain\Utils\MathHelper;
use App\Infrastructure\Doctrine\Entity\FemaleTournament as FemaleTournamentEntity;
use App\Infrastructure\Doctrine\Entity\MaleTournament as MaleTournamentEntity;
use Doctrine\ORM\EntityManagerInterface;

class PlayTournamentUseCase
{
    public function __construct(
        private EntityManagerInterface $em,
        private GeneratePlayersService $generatePlayersService
    )
    {
    }

    /**
     * @param GenderEnum $type
     * @param Player[] $players
     *
     * @return Player
     */
    public function execute(GenderEnum $type, int $count): Player
    {
        if (!MathHelper::isPowerOfTwo($count)) {
            throw new \InvalidArgumentException("Player count must be a power of 2.");
        }

        $players = $this->generatePlayersService->generate($count, $type);

        $tournament = match ($type) {
            GenderEnum::MALE => new MaleTournament($players),
            GenderEnum::FEMALE => new FemaleTournament($players),
        };

        $winner = $tournament->play();

        $entity = match ($type) {
            GenderEnum::MALE => new MaleTournamentEntity(),
            GenderEnum::FEMALE => new FemaleTournamentEntity(),
        };

        $this->em->persist($entity);
        $this->em->flush();

        return $winner;
    }

}