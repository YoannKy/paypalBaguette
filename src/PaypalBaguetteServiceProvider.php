<?php

namespace Yoannky\Paypalbaguette;

use Illuminate\Support\ServiceProvider;

class PaypalBaguetteServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__.'/config/paypalbaguette.php' => config_path('paypalbaguette.php'),]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->bind('paypalbaguette', function()
        {
            return new PaypalBaguette;
        });
    }
}
