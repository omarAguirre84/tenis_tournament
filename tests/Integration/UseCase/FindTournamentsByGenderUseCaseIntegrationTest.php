<?php

namespace App\Tests\Integration\UseCase;

use App\Application\UseCase\FindTournamentsByGenderUseCase;
use App\Domain\Enum\GenderEnum;
use App\Infrastructure\Doctrine\Entity\FemaleTournament;
use App\Infrastructure\Doctrine\Entity\MaleTournament;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FindTournamentsByGenderUseCaseIntegrationTest extends KernelTestCase
{
    private EntityManagerInterface $em;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        $this->clearDatabase();
    }

    private function clearDatabase(): void
    {
        $this->em->createQuery('DELETE FROM App\Infrastructure\Doctrine\Entity\Tournament')->execute();
    }

    public function testItReturnsOnlyMaleTournaments()
    {
        $t1 = new MaleTournament();
        $t2 = new MaleTournament();
        $t3 = new FemaleTournament();

        $this->em->persist($t1);
        $this->em->persist($t2);
        $this->em->persist($t3);
        $this->em->flush();

        $useCase = static::getContainer()->get(FindTournamentsByGenderUseCase::class);

        $result = $useCase->execute(GenderEnum::MALE);

        $this->assertCount(2, $result);
        $this->assertContainsOnlyInstancesOf(MaleTournament::class, $result);
    }

    public function testItReturnsOnlyFemaleTournaments()
    {
        $t1 = new MaleTournament();
        $t2 = new FemaleTournament();
        $t3 = new FemaleTournament();

        $this->em->persist($t1);
        $this->em->persist($t2);
        $this->em->persist($t3);
        $this->em->flush();

        $useCase = static::getContainer()->get(FindTournamentsByGenderUseCase::class);

        $result = $useCase->execute(GenderEnum::FEMALE);

        $this->assertCount(2, $result);
        $this->assertContainsOnlyInstancesOf(FemaleTournament::class, $result);
    }
}
