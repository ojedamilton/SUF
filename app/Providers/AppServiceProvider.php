<?php

namespace App\Providers;

use App\Models\DetalleCompra;
use App\Models\DetalleFactura;
use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Observers\MailObserver;
use App\Observers\StockCompraObserver;
use App\Observers\StockFacturaObserver;

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
        // Cuando el usuario se crea, se envia un mail
        User::observe(MailObserver::class);
        // Cuando se crea un detalle de factura, se disminuye el stock
        DetalleFactura::observe(StockFacturaObserver::class);
        // Cuando se crea un detalle de compra, se suma el stock
        DetalleCompra::observe(StockCompraObserver::class);
    }
}
