<?php

namespace App\Jobs;

use App\Services\Api\Interfaces\GameServiceInterface;
use App\Services\Handler\Interfaces\HandlerServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GameJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(HandlerServiceInterface $handlerService): void
    {
        $handlerService->getCurrentRound();
        $game = $handlerService->getCurrentGame();

        if (!$game->isUniverseFetched()) {
            $handlerService->fetchUniverse($game);
        }

        ProcessJob::dispatch('search');
    }
}
