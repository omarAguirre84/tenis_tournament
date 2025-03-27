<?php

namespace App\Infrastructure\Controller;

use App\Application\DTO\TournamentResponseDTO;
use App\Application\UseCase\FindTournamentsByDateRangeUseCase;
use App\Application\UseCase\FindTournamentsByGenderUseCase;
use App\Application\UseCase\PlayTournamentUseCase;
use App\Domain\Enum\GenderEnum;
use App\Domain\Utils\DateParser;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

class TournamentController extends AbstractController
{
    #[OA\Get(
        path: '/tournaments/play',
        summary: 'Play a tournament',
        tags: ['Tournaments'],
        parameters: [
            new OA\Parameter(
                name: 'type',
                description: 'Tournament type',
                in: 'query',
                required: true,
                schema: new OA\Schema(type: 'string', enum: ['male', 'female'])
            ),
            new OA\Parameter(
                name: 'count',
                description: 'Number of players',
                in: 'query',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'The winner of the tournament',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'winner', type: 'string'),
                        new OA\Property(property: 'type', type: 'string'),
                    ]
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
    #[Route('/tournaments/play', name: 'tournament_play', methods: ['GET'])]
    public function index(Request               $request,
                          PlayTournamentUseCase $useCase): JsonResponse
    {
        $type = GenderEnum::tryFrom($request->get('type'));
        $count = (int)$request->get('count');

        try {
            if (!$type) {
                throw new InvalidArgumentException('Invalid tournament type.');
            }

            $winner = $useCase->execute($type, $count);

            return $this->json([
                'winner' => $winner->getName(),
                'type'   => (new \ReflectionClass($winner))->getShortName(),
            ]);

        } catch (\Throwable $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[OA\Get(
        path: '/tournaments/gender/{type}',
        summary: 'Get tournaments by gender',
        tags: ['Tournaments'],
        parameters: [
            new OA\Parameter(
                name: 'type',
                description: 'Gender of the tournament',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', enum: ['male', 'female'])
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of tournaments',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'name', type: 'string'),
                            new OA\Property(property: 'date', type: 'string', format: 'date')
                        ],
                        type: 'object'
                    )
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid gender'
            )
        ]
    )]
    #[Route('/tournaments/gender/{type}', name: 'tournaments_get_by_gender', methods: ['GET'])]
    public function getByGender(string $type, FindTournamentsByGenderUseCase $useCase): JsonResponse
    {
        $gender = GenderEnum::tryFrom($type);

        try {
            if (!$gender) {
                throw new InvalidArgumentException('Invalid gender.');
            }
            $result = $useCase->execute($gender);

            $responseDto = array_map(
                fn($r) => TournamentResponseDTO::fromEntity($r),
                $result
            );

            return $this->json($responseDto, Response::HTTP_OK);

        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[OA\Get(
        path: '/tournaments/dates',
        summary: 'Get tournaments by date range',
        tags: ['Tournaments'],
        parameters: [
            new OA\Parameter(
                name: 'from',
                description: 'Start date (YYYY-MM-DD)',
                in: 'query',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'date')
            ),
            new OA\Parameter(
                name: 'to',
                description: 'End date (YYYY-MM-DD)',
                in: 'query',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'date')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of tournaments in date range',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'name', type: 'string'),
                            new OA\Property(property: 'date', type: 'string', format: 'date')
                        ],
                        type: 'object'
                    )
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid or missing date format'
            )
        ]
    )]
    #[Route('/tournaments/dates', name: 'tournaments_get_by_date', methods: ['GET'])]
    public function getByDate(Request $request, FindTournamentsByDateRangeUseCase $useCase): JsonResponse
    {
        $from = $request->query->get('from');
        $to = $request->query->get('to');

        if (!$from || !$to) {
            return $this->json(['error' => 'Invalid date format. Use YYYY-MM-DD.'], Response::HTTP_BAD_REQUEST);
        }

        $from = DateParser::parseExact($from);
        $to = DateParser::parseExact($to);

        try {
            $result = $useCase->execute($from, $to);

            $responseDto = array_map(
                fn($r) => TournamentResponseDTO::fromEntity($r),
                $result
            );

            return $this->json($responseDto, Response::HTTP_OK);

        } catch (\Throwable $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

}
