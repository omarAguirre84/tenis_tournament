<?php

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class PlayerControllerTest extends WebTestCase
{
    public function testGeneratePlayersReturnsValidResponse(): void
    {
        $client = static::createClient();

        // Solicita generación de jugadores con tipo masculino y número 5
        $client->request('GET', '/players/generate/male/5');

        // Verifica que la respuesta sea exitosa (200 OK)
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        // Verifica que el contenido devuelto sea un array de nombres
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($data);
        $this->assertCount(5, $data); // Deben generarse 5 nombres
    }

    public function testGeneratePlayersWithInvalidType(): void
    {
        $client = static::createClient();

        // Solicita generación de jugadores con un tipo inválido
        $client->request('GET', '/players/generate/unknown/5');

        // Verifica que se reciba un error
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('Invalid type. Use male or female.', $data['error']);
    }

    public function testGeneratePlayersWithInvalidCount(): void
    {
        $client = static::createClient();

        // Solicita generación de jugadores con un número de jugadores inválido
        $client->request('GET', '/players/generate/male/0');

        // Verifica que se reciba un error
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('error', $data);
    }

    public function testCleanPlayerCacheReturnsValidResponse(): void
    {
        $client = static::createClient();

        // Solicita limpiar el cache para jugadores masculinos
        $client->request('DELETE', '/players/clean/male');

        // Verifica que la respuesta sea exitosa (200 OK)
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        // Verifica que la respuesta contenga el estado correcto
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('status', $data);
        $this->assertEquals('ok', $data['status']);
    }

    public function testCleanPlayerCacheWithInvalidGender(): void
    {
        $client = static::createClient();

        // Solicita limpiar el cache para un género inválido
        $client->request('DELETE', '/players/clean/unknown');

        // Verifica que se reciba un error
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('Invalid gender. Use "male" or "female".', $data['error']);
    }

}
