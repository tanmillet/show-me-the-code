<?php

namespace TerryLucas2017\Pattern;

use Illuminate\Support\ServiceProvider;

class LucasPatternProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->singleton(
            'lucaspattern',
            function () {
                return new LucasPattern();
            }
        );
    }
}
