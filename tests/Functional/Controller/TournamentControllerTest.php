<?php

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TournamentControllerTest extends WebTestCase
{
    public function testPlayTournamentReturnsValidResponse(): void
    {
        $client = static::createClient();

        $client->request('GET', '/tournaments/play?type=male&count=8');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('winner', $data);
        $this->assertArrayHasKey('type', $data);
        $this->assertNotEmpty($data['winner']);
    }

    public function testPlayTournamentWithInvalidType(): void
    {
        $client = static::createClient();

        $client->request('GET', '/tournaments/play?type=invalid&count=8');

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('error', $data);
    }

    public function testPlayTournamentWithInvalidCount(): void
    {
        $client = static::createClient();

        $client->request('GET', '/tournaments/play?type=male&count=3');

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('error', $data);
    }

    public function testGetTournamentsByGenderReturnsValidResponse(): void
    {
        $client = static::createClient();

        $client->request('GET', '/tournaments/gender/male');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($data);
        $this->assertNotEmpty($data);
    }

    public function testGetTournamentsByGenderWithInvalidGender(): void
    {
        $client = static::createClient();

        $client->request('GET', '/tournaments/gender/invalid');

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('error', $data);
    }

    public function testGetTournamentsByDateReturnsValidResponse(): void
    {
        $client = static::createClient();

        $client->request('GET', '/tournaments/dates?from=2025-01-01&to=2025-12-31');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($data);
        $this->assertNotEmpty($data);
    }

    public function testGetTournamentsByDateWithInvalidDateFormat(): void
    {
        $client = static::createClient();

        $client->request('GET', '/tournaments/dates?from=01-01-2025&to=31-12-2025');

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('error', $data);
    }

    public function testGetTournamentsByDateWithMissingParameters(): void
    {
        $client = static::createClient();

        $client->request('GET', '/tournaments/dates');

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('Invalid date format. Use YYYY-MM-DD.', $data['error']);
    }

}
