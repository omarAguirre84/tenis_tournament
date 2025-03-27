<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Enum\GenderEnum;
use App\Domain\Repository\TournamentRepositoryInterface;
use App\Domain\Utils\DateRangeValidator;
use App\Infrastructure\Doctrine\Entity\Tournament;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TournamentRepository extends ServiceEntityRepository implements TournamentRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tournament::class);
    }

    public function findByGender(GenderEnum $gender): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere($gender->value === 'male'
                ? 't INSTANCE OF App\\Infrastructure\\Doctrine\\Entity\\MaleTournament'
                : 't INSTANCE OF App\\Infrastructure\\Doctrine\\Entity\\FemaleTournament')
            ->orderBy('t.playedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByDateRange(\DateTimeInterface $from, \DateTimeInterface $to): array
    {
        DateRangeValidator::validate($from, $to);

        $from = \DateTimeImmutable::createFromInterface($from)->setTime(0, 0, 1);
        $to = \DateTimeImmutable::createFromInterface($to)->setTime(23, 59, 59);

        return $this->createQueryBuilder('t')
            ->andWhere('t.playedAt >= :from')
            ->andWhere('t.playedAt <= :to')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->orderBy('t.playedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}