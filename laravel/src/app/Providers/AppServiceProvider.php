<?php

namespace App\Providers;

use ClickHouseDB\Client;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Client::class, function ($app) {
            return new Client([
                'host' => env('CLICKHOUSE_HOST', '127.0.0.1'),
                'port' => env('CLICKHOUSE_PORT', '8123'),
                'username' => env('CLICKHOUSE_USERNAME', 'mydesole'),
                'password' => env('CLICKHOUSE_PASSWORD', '1008asdt'),
            ]);
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
