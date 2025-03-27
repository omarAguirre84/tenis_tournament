<?php

namespace App\Infrastructure\Service;

use App\Domain\Service\NameGeneratorServiceInterface;

class StaticNameGeneratorService implements NameGeneratorServiceInterface
{
    private array $maleFirst = [
        "Sergio", "Jaime", "Lorenzo", "Hugo", "Sebastián", "Sergio", "Aitor",
        "Enrique", "Christian", "Julián", "Miguel", "Alfredo", "Joaquin", "Domingo", "Ricardo", "Gerardo",
        "Pablo", "Iván", "Joan", "Marco",];
    private array $femaleFirst = ["Enni", "Laurie", "Lydia", "Marsha", "Alice", "Alwina", "Elsie", "Celia",
                                  "Elisa", "Violet", "Eevi", "Rosa", "Rebecca", "Phoebe", "Sarah", "Katrine", "Elizabeth", "Barbara",
                                  "Sara", "Noémie", "Svitovida", "Carmen", "Teresa", "María Luisa", "Alexandra",
                                  "Vilina", "Alicia", "Maeva", "Grace", "Melinda", "Sanjana", "Anna", "Daniella", "Peppi", "Amy",
                                  "Nicole", "Antonia", "Deeksha", "Emma", "Maya", "Cassandra", "Rathi", "Pava", "Maria",
                                  "Isla", "Annemarije", "Olav"];
    private array $lastNames = [
        "Moreno", "Muñoz", "Velasco", "Castillo", "Pastor", "Domínguez", "Cabrera",
        "Parra", "Ruiz", "Gutiérrez", "Méndez", "Gallardo", "Pascual", "Méndez", "Caballero",
        "Flores", "Prieto", "Sáez", "Cabrera", "Pérez"
    ];

    public function getRandomNames(string $gender, int $count): array
    {
        $firstPool = match ($gender) {
            'male' => $this->maleFirst,
            'female' => $this->femaleFirst,
            default => throw new \InvalidArgumentException("Invalid gender: $gender"),
        };

        $firstNames = $this->getRandom($firstPool, $count);
        $lastNames = $this->getRandom($this->lastNames, $count);

        $names = [];
        for ($i = 0; $i < $count; $i++) {
            $names[] = [
                'first' => $firstNames[$i],
                'last'  => $lastNames[$i],
            ];
        }

        return $names;
    }

    private function getRandom(array $source, int $count): array
    {
        if (count($source) < $count) {
            throw new \RuntimeException('Not enough names to generate');
        }

        shuffle($source);
        return array_slice($source, 0, $count);
    }
}
