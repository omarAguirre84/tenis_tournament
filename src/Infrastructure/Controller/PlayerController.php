<?php

namespace App\Infrastructure\Controller;

use App\Application\UseCase\CleanPlayerCacheUseCase;
use App\Application\UseCase\GeneratePlayersUseCase;
use App\Domain\Enum\GenderEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

class PlayerController extends AbstractController
{
    #[OA\Get(
        path: '/players/generate/{type}/{count}',
        summary: 'Generate a list of players',
        tags: ['Players'],
        parameters: [
            new OA\Parameter(
                name: 'type',
                description: 'Gender of the players',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', enum: ['male', 'female'])
            ),
            new OA\Parameter(
                name: 'count',
                description: 'Number of players to generate',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of generated player names',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(type: 'string')
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid input',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string')
                    ]
                )
            )
        ]
    )]
    #[Route('/players/generate/{type}/{count}', name: 'players_generate', methods: ['GET'])]
    public function generate(string $type, int $count, GeneratePlayersUseCase $useCase): JsonResponse
    {
        // Verifica si el número de jugadores es válido
        if ($count <= 0) {
            return $this->json(['error' => 'Count must be greater than 0.'], 400);
        }

        $genderType = GenderEnum::tryFrom($type);
        if (!$genderType) {
            return $this->json(['error' => 'Invalid type. Use ' .
                GenderEnum::MALE->value . ' or ' . GenderEnum::FEMALE->value . '.'
            ], 400);
        }

        $players = $useCase->execute($genderType, $count);
        $response = array_map(fn($player) => $player->getName(), $players);

        return new JsonResponse(
            json_encode($response, JSON_UNESCAPED_UNICODE),
            Response::HTTP_OK,
            [],
            true
        );
    }

    #[OA\Delete(
        path: '/players/clean/{type}',
        summary: 'Clean cached players by gender',
        tags: ['Players'],
        parameters: [
            new OA\Parameter(
                name: 'type',
                description: 'Gender to clean cache for',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', enum: ['male', 'female'])
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Cache cleaned successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'ok')
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid gender',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string')
                    ]
                )
            )
        ]
    )]
    #[Route('/players/clean/{type}', name: 'players_clean', methods: ['DELETE'])]
    public function clean(string $type, CleanPlayerCacheUseCase $useCase): JsonResponse
    {
        $gender = GenderEnum::tryFrom($type);

        if (!$gender) {
            return $this->json([
                'error' => 'Invalid gender. Use "' . GenderEnum::MALE->value . '" or "' . GenderEnum::FEMALE->value . '".'
            ], 400);
        }

        $useCase->execute($gender);

        return $this->json(['status' => 'ok']);
    }
}
