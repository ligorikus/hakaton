<?php

namespace App\Providers;

use App\Jobs\GameJob;
use App\Jobs\ProcessJob;
use App\Services\Api\GameService;
use App\Services\Api\Interfaces\GameServiceInterface;
use App\Services\GarbageCollector\GarbageCollectorService;
use App\Services\GarbageCollector\Interfaces\GarbageCollectorInterface;
use App\Services\Handler\HandlerService;
use App\Services\Handler\Interfaces\HandlerServiceInterface;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(GameServiceInterface::class, function () {
            $pendingRequest = Http::baseUrl(config('game.url'))
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X-Auth-Token' => config('game.token'),
                ]);
            return new GameService($pendingRequest);
        });

        $this->app->bind(HandlerServiceInterface::class, HandlerService::class);
        $this->app->bind(GarbageCollectorInterface::class, GarbageCollectorService::class);

        $this->app->bindMethod([GameJob::class, 'handle'], function (GameJob $job, Application $app) {
            return $job->handle(
                $app->make(HandlerServiceInterface::class)
            );
        });


        $this->app->bindMethod([ProcessJob::class, 'handle'], function (ProcessJob $job, Application $app) {
            return $job->handle(
                $app->make(GameServiceInterface::class),
                $app->make(HandlerServiceInterface::class),
                $app->make(GarbageCollectorInterface::class),
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
