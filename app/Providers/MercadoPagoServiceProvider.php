<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use MercadoPago\MercadoPagoConfig;

class MercadoPagoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        MercadoPagoConfig::setAccessToken(env('MP_ACCESS_TOKEN'));
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
