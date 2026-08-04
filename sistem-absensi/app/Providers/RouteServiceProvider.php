<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->routes(function () {
            if (file_exists($path = base_path('routes/web.php'))) {
                require $path;
            }

            if (file_exists($path = base_path('routes/api.php'))) {
                require $path;
            }

            if (file_exists($path = base_path('routes/admin.php'))) {
                require $path;
            }

            if (file_exists($path = base_path('routes/pegawai.php'))) {
                require $path;
            }

            if (file_exists($path = base_path('routes/auth.php'))) {
                require $path;
            }
        });
    }
}
