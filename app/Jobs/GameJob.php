<?php

namespace App\Jobs;

use App\Services\Api\Interfaces\GameServiceInterface;
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
    public function __construct(private $item)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(GameServiceInterface $gameService): void
    {
        $result = $gameService->test();
        var_dump($result);
        if ($this->item === 1)
            return;
        var_dump($this->item);
        GameJob::dispatch($this->item+1);
    }
}
