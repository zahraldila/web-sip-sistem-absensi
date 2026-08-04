<?php

namespace App\Providers;

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
        // Load global helpers if present
        $helpers = app_path('Helpers/helpers.php');
        if (file_exists($helpers)) {
            require_once $helpers;
        }
    }
}
