<?php

namespace App\Console\Commands;

use App\Services\Api\Interfaces\GameServiceInterface;
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
    public function handle(GameServiceInterface $gameService)
    {
        dd($gameService->universe()->getShip()->getPlanet());
    }
}
