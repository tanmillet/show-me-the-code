<?php

namespace TerryLucas2017\Hasher;

use Illuminate\Support\ServiceProvider;

/**
 * Class LucasMD5Provider.
 *
 * User: Terry Lucas
 */
class LucasMD5Provider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        //
        $this->app->singleton(
            'lucasmd5',
            function () {
                return new LucasMD5();
            }
        );
    }
}
