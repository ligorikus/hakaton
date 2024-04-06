<?php

namespace App\Console\Commands;

use App\Models\Edge;
use App\Models\Garbage;
use App\Services\Api\Dto\GarbageDto;
use App\Services\Api\Interfaces\GameServiceInterface;
use App\Services\GarbageCollector\Interfaces\GarbageCollectorInterface;
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
    public function handle(GarbageCollectorInterface $garbageCollector, GameServiceInterface $gameService)
    {
        $item = Garbage::find(1);
        $item = new GarbageDto($item->key, json_decode($item->data));

        $ids = [39,176,53,247,72,93,246,25,122,146,301,99,219,45,329,42,245,78,252,276];
        $res = Garbage::inRandomOrder()->limit(random_int(20,30))->get();

        $garbages = [];
        foreach ($res as $item) {
            $garbages[] = new GarbageDto($item->key, json_decode($item->data));
        }
        $garbageCollector->collect([], $garbages);
    }
}
