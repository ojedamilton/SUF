<?php

namespace App\Providers;

use App\Models\DetalleFactura;
use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Observers\MailObserver;
use App\Observers\StockObserver;

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
        User::observe(MailObserver::class);
        DetalleFactura::observe(StockObserver::class);
    }
}
