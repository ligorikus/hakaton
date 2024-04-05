<?php

namespace App\Console\Commands;

use App\Models\Round;
use App\Services\Api\Dto\RoundDto;
use App\Services\Api\Interfaces\GameServiceInterface;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GetRounds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-rounds';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct(private GameServiceInterface $gameService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
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
}
