<?php

namespace App\Services\Handler;

use App\Models\Edge;
use App\Models\Game;
use App\Models\Round;
use App\Services\Api\Dto\EdgeDto;
use App\Services\Api\Dto\RoundDto;
use App\Services\Api\Dto\UniverseDto;
use App\Services\Api\Interfaces\GameServiceInterface;
use App\Services\Handler\Dto\GameDto;
use App\Services\Handler\Interfaces\HandlerServiceInterface;


class HandlerService implements HandlerServiceInterface
{
    public function __construct(private GameServiceInterface $gameService)
    {
    }

    public function getCurrentRound(): ?RoundDto
    {
        /** @var Round $round */
        $round = Round::where('is_current', true)->first();
        return $round?->toDto();
    }

    public function getCurrentGame(): GameDto
    {
        $round = $this->getCurrentRound();

        if ($round === null) {
            throw new \Exception("игра не запущена");
        }

        /** @var Game $game */
        $game = Game::where('round_id', $round->getId())->firstOrCreate([
            'round_id' => $round->getId()
        ]);

        return $game->toDto();
    }

    public function fetchUniverse(GameDto $game): bool
    {
        try {
            $edges = $this->gameService->universe()->getEdges();
            /** @var EdgeDto $edge */
            foreach ($edges as $edge) {
                Edge::create([
                    'departure' => $edge->getDeparture(),
                    'destination' => $edge->getDestination(),
                    'cost' => $edge->getCost()
                ]);
            }

            $game->setUniverseFetched(true);
            $game->toDB();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }
}
