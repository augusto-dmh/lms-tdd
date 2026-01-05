<?php

namespace App\Providers;

use App\ApiClients\PaddleBillingApiClient;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(PaddleBillingApiClient::class, function () {
            return new PaddleBillingApiClient(
                apiKey: config('services.paddle.api_key'),
                baseUrl: config('services.paddle.base_url'),
                apiVersion: config('services.paddle.api_version', '1'),
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
