<?php

namespace App\Tests\Integration\UseCase;

use App\Application\UseCase\FindTournamentsByDateRangeUseCase;
use App\Infrastructure\Doctrine\Entity\MaleTournament;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FindTournamentsByDateRangeUseCaseIntegrationTest extends KernelTestCase
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

    public function testItReturnsTournamentsWithinDateRange(): void
    {
        // Seeds
        $t1 = new MaleTournament(); // hoy
        $t2 = new MaleTournament(); // -5 days
        $t3 = new MaleTournament(); // -10 days

        $this->setPlayedAt($t1, new \DateTimeImmutable());
        $this->setPlayedAt($t2, (new \DateTimeImmutable())->modify('-5 days'));
        $this->setPlayedAt($t3, (new \DateTimeImmutable())->modify('-10 days'));

        $this->em->persist($t1);
        $this->em->persist($t2);
        $this->em->persist($t3);
        $this->em->flush();

        /** @var FindTournamentsByDateRangeUseCase $useCase */
        $useCase = static::getContainer()->get(FindTournamentsByDateRangeUseCase::class);

        $from = (new \DateTimeImmutable())->modify('-6 days');
        $to = new \DateTimeImmutable();

        $results = $useCase->execute($from, $to);

        $this->assertCount(2, $results);
        $this->assertContainsOnlyInstancesOf(MaleTournament::class, $results);
    }

    private function setPlayedAt(MaleTournament $tournament, \DateTimeImmutable $date): void
    {
        $ref = new \ReflectionProperty($tournament, 'playedAt');
        $ref->setAccessible(true);
        $ref->setValue($tournament, $date);
    }
}

