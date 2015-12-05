<?php

namespace App\Providers;

use App\Infrastructure\DBConnection;
use App\Repository\Entity\AlertQueueItem;
use App\Repository\Entity\Station;
use App\Repository\StationRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Initialize database connection
        DBConnection::getConnection([
            'driver' => 'mysql',
            'host' => env('DB_HOST', 'localhost'),
            'dbname' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8'
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
