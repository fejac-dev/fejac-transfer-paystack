<?php

namespace Mybakery\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema; //NEW: Import Schema
use Illuminate\Support\Facades\URL;
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
        //
        Schema::defaultStringLength(191); //NEW: Increase StringLength
        if ($this->app->environment() == 'production') {
            URL::forceScheme('https');
        }
    }
}
