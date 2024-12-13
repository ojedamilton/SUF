<?php

namespace App\Providers;

use App\Models\DetalleCompra;
use App\Models\DetalleFactura;
use App\Models\DetalleNotaCredito;
use App\Models\Factura;
use App\Models\NotaCredito;
use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Observers\FacturaObserver;
use App\Observers\NotaCreditoObserver;
use App\Observers\MailObserver;
use App\Observers\StockCompraObserver;
use App\Observers\StockFacturaObserver;
use App\Observers\StockNotaCreditoObserver;

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
        // Cuando se crea un detalle de factura, se disminuye el stock(DetalleFactura es observado por StockFacturaObserver)
        DetalleFactura::observe(StockFacturaObserver::class);
        // Cuando se crea un detalle de compra, se suma el stock(DetalleCompra es observado por StockCompraObserver)
        DetalleCompra::observe(StockCompraObserver::class);
        // Cuando se crea una nota de crédito, suma el stock(DetalleNotaCredito es observado por StockNotaCreditoObserver)
        DetalleNotaCredito::observe(StockNotaCreditoObserver::class);
        // Cuando se crea una factura, se crea una linea en el trancomp.txt
        Factura::observe(FacturaObserver::class);        
        // Cuando se crea una nc, se crea una linea en el trancomp.txt
        NotaCredito::observe(NotaCreditoObserver::class); 
    }
}
