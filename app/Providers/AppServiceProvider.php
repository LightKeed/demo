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
        setlocale(LC_TIME, "ru_RU.UTF-8");
        \Carbon\Carbon::setLocale("ru");
//        \Illuminate\Support\Facades\URL:: forceScheme("https");
//        $this->app['request']->server->set('HTTPS','on');
    }
}
