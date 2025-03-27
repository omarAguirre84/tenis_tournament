<?php

namespace App\Tests\Unit\Infrastructure\Doctrine\Repository;

use App\Domain\Enum\GenderEnum;
use App\Infrastructure\Doctrine\Entity\FemaleTournament;
use App\Infrastructure\Doctrine\Entity\MaleTournament;
use App\Infrastructure\Doctrine\Repository\TournamentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TournamentRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $em;
    private TournamentRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        $this->repository = static::getContainer()->get(TournamentRepository::class);

        // Limpiar y crear esquema en SQLite in-memory
        $schemaTool = new SchemaTool($this->em);
        $metadata = $this->em->getMetadataFactory()->getAllMetadata();

        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);
    }

    public function testFindByGenderReturnsCorrectTournaments(): void
    {
        $this->em->persist(new MaleTournament());
        $this->em->persist(new MaleTournament());
        $this->em->persist(new FemaleTournament());
        $this->em->flush();

        $males = $this->repository->findByGender(GenderEnum::MALE);
        $females = $this->repository->findByGender(GenderEnum::FEMALE);

        $this->assertCount(2, $males);
        $this->assertCount(1, $females);
    }
}

