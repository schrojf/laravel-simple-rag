<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class DevServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->isLocal()) {
            $this->loadDevRoutes();
        }
    }

    /**
     * Load debug/development routes
     */
    protected function loadDevRoutes(): void
    {
        Route::group([
            'prefix' => '_dev',
            'middleware' => ['web'],
        ], function () {
            Route::get('/style-guide', function () {
                return view('dev.style-guide');
            })->name('_dev.style-guide');
        });
    }
}
