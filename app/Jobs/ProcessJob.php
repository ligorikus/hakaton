<?php

namespace App\Jobs;

use App\Models\Planet;
use App\Services\Api\Dto\GarbageDto;
use App\Services\Api\Dto\ShipDto;
use App\Services\Api\Dto\TravelDto;
use App\Services\Api\Interfaces\GameServiceInterface;
use App\Services\GarbageCollector\Interfaces\GarbageCollectorInterface;
use App\Services\Handler\Interfaces\HandlerServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private GameServiceInterface $gameService;
    private HandlerServiceInterface $handlerService;
    private GarbageCollectorInterface $garbageCollector;

    private $current;
    /** @var ShipDto */
    private $ship;

    /**
     * Create a new job instance.
     */
    public function __construct(private $type, private $params = [])
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(GameServiceInterface $gameService, HandlerServiceInterface $handlerService, GarbageCollectorInterface $garbageCollector): void
    {
        $this->gameService = $gameService;
        $this->handlerService = $handlerService;
        $this->garbageCollector = $garbageCollector;

        $universe = $this->gameService->universe();
        $this->ship = $universe->getShip();
        $this->current = $this->ship->getPlanet()->getName();

        switch ($this->type) {
            case 'search':
                $this->search();
                break;
            case 'move':
                $this->move();
                break;
            case 'collect':
                $this->collect();
                break;
        }
    }

    private function search()
    {
        $perc = $this->handlerService->shipGarbagePerc($this->ship->getGarbage());

        if ($perc > 50) {
            self::dispatch('move', ['dest' => 'Eden']);
            return;
        }

        $planet = $this->handlerService->getNear($this->current);

        if ($planet === null) {
            $planet = Planet::where('cheked', false)->where('cleared', false)->first()->name;
        }
        if ($planet === null) {
            self::dispatch('move', ['dest' => 'Eden']);
            return;
        }
        self::dispatch('move', ['dest' => $planet]);
    }

    private function move()
    {
        $path = $this->handlerService->pathfinder($this->current, $this->params['dest']);
        $travel = $this->gameService->travel(array_values($path));
        ProcessJob::dispatch('collect', ['travel' => $travel]);
    }

    private function collect()
    {
        /** @var TravelDto $travelDto */
        $travelDto = $this->params['travel'];
        $planetGarbage = $travelDto->getPlanetGarbage();

        $result = $this->garbageCollector->collect($this->ship->getGarbage(), $planetGarbage);
        $a = [];
        /** @var GarbageDto $garbage */
        foreach ($result['shipGarbage'] as $garbage) {
            if (!empty($garbage->getItems())) {
                $a[$garbage->getKey()] = $garbage->getItems();
            }

        }
        $this->gameService->collect($a);
        ProcessJob::dispatch('search');
    }
}
