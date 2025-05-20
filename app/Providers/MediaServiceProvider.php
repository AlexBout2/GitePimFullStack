<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MediaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('media', function ($app) {
            return new \App\Services\MediaService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
