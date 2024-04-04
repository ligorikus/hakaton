<?php

namespace App\Providers;

use App\Services\Api\ApiService;
use App\Services\Api\Interfaces\ApiServiceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ApiServiceInterface::class, function () {
            $pendingRequest = Http::baseUrl(config('api.url'));
            return new ApiService($pendingRequest);
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
