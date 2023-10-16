<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Cache\Factory;
use App\Models\Setting;

class SettingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Factory $cache, Setting $settings)
    {
        //переделать это говно
        if(!app()->runningInConsole()) {
            $settings = $cache->remember('settings', 60, function() use ($settings){
                return $settings->pluck('value', 'name')->all() ?? [];
            });

            config()->set('settings', $settings ?? []);
        }
    }
}

