<?php

namespace App\Services\Handler;

use App\Models\Edge;
use App\Models\Game;
use App\Models\Planet;
use App\Models\Round;
use App\Services\Api\Dto\EdgeDto;
use App\Services\Api\Dto\RoundDto;
use App\Services\Api\Dto\UniverseDto;
use App\Services\Api\Interfaces\GameServiceInterface;
use App\Services\GarbageCollector\Interfaces\GarbageCollectorInterface;
use App\Services\Handler\Dto\GameDto;
use App\Services\Handler\Interfaces\HandlerServiceInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class HandlerService implements HandlerServiceInterface
{
    public function __construct(private GameServiceInterface $gameService, private GarbageCollectorInterface $garbageCollector)
    {
    }

    private function getRounds(): void
    {
        $rounds = $this->gameService->rounds();
        DB::table('rounds')->truncate();
        /** @var RoundDto $round */
        foreach ($rounds as $round) {
            Round::create([
                'name' => $round->getName(),
                'start_at' => Carbon::make($round->getStartAt()),
                'end_at' => Carbon::make($round->getEndAt()),
                'is_current' => $round->isCurrent(),
                'planet_count' => $round->getPlanetCount(),
            ]);
        }
    }

    public function getCurrentRound(): ?RoundDto
    {
        $this->getRounds();
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
            DB::table('edges')->truncate();
            DB::table('planets')->truncate();
            $edges = $this->gameService->universe()->getEdges();
            /** @var EdgeDto $edge */
            foreach ($edges as $edge) {
                Edge::create([
                    'departure' => $edge->getDeparture(),
                    'destination' => $edge->getDestination(),
                    'cost' => $edge->getCost()
                ]);
            }

            foreach (Edge::all()->pluck('departure')->unique() as $planet) {
                Planet::create([
                    'name' => $planet
                ]);
            }

            $game->setUniverseFetched(true);
            $game->toDB();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public function pathfinder($from, $to): array
    {
        $edges = Edge::all();
        $planets = [];
        foreach ($edges as $edge) {
            $planets[$edge->departure]['paths'][] = ['destination' => $edge->destination, 'cost' => $edge->cost];
            $planets[$edge->departure]['done'] = false;
        }

        return (new Pathined($planets))->find($from, $to);
    }

    public function shipGarbagePerc($shipGarbage)
    {
        $sg811 = $this->garbageCollector->shipGarbage811($shipGarbage);
        return $this->percentageZagr($sg811);
    }

    public function getNear($currrent)
    {
        $edges = Edge::with('planets')->whereNot('destination', $currrent)->where('departure', $currrent)->get()->pluck('planets')->collapse();
        $valid = [];
        foreach ($edges as $edge) {
            if ($edge->cleared) {
                continue;
            }
            if (!$edge->checked || $edge->garbage !== null) {
                $valid[] = $edge;
            }
        }
        return $valid[0]?->name;
    }

    public function moveToEden($current)
    {
        $this->pathfinder($current, 'Eden');
    }

    public function percentageZagr($sg811)
    {
        return $this->garbageCollector->calcZagruzka($sg811) / 88;
    }
}
