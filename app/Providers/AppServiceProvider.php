<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // components.badge get from component > badge.blade.ph
        // give it a name and call @badge(['type' => 'primary']) directive
        // @components('components.badge') has to provide path but @badge([]) provide in AppServiceProvider
        Blade::component('components.badge', 'badge');
    }
}
