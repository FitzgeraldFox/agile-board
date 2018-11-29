<?php

namespace App\Providers;

use App\Facades\SprintService;
use Illuminate\Support\ServiceProvider;

class SprintServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('sprint', function() {
            return new SprintService();
        });
    }
}
