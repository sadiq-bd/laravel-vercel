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
        // force https when application is in production mode && not using localhost
        if($this->app->environment('production')) {
            if (!empty($_SERVER['HTTP_HOST']) && !preg_match('/(127\.0\.0\.\d+|192\.168\.\d+\.\d+|localhost)(\:\d+)?/i', $_SERVER['HTTP_HOST'])) {
                    \URL::forceScheme('https');
            }
                 
         }
        
    }
}
