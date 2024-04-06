<?php

namespace App\Console\Commands;

use App\Models\Edge;
use App\Models\Garbage;
use App\Services\Api\Dto\GarbageDto;
use App\Services\Api\Interfaces\GameServiceInterface;
use App\Services\GarbageCollector\Interfaces\GarbageCollectorInterface;
use App\Services\Handler\Interfaces\HandlerServiceInterface;
use Illuminate\Console\Command;

class Debug extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:debug';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(GarbageCollectorInterface $garbageCollector, GameServiceInterface $gameService, HandlerServiceInterface $handlerService)
    {
        $shipGarbage = $gameService->universe()->getShip()->getPlanet();
        dd($shipGarbage);
    }
}
