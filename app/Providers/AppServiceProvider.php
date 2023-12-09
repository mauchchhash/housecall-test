<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // setup a Http macro for RxNormApi
        Http::macro('RxNormApi', function () {
            return Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->baseUrl(config('services.rx-norm.base-url'));
        });
    }
}
