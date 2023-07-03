<?php

namespace App\Observers;

use App\Models\Factura;
use App\Models\Stock;
use App\Models\Articulo;
use App\Models\DetalleFactura;
use Illuminate\Support\Facades\Log;

class StockObserver
{
     /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(DetalleFactura $detallefactura)
    {
        // Logeo de Creacion Detalle Factura
        Log::info("Detalle Factura creada: id->".$detallefactura->id." NumeroFactura->".$detallefactura->idFactura." idArticulo->".$detallefactura->idArticulo." Cantidad->".$detallefactura->cantidadArticulo);
        try {
            // Refresco Instancia $factura recien creada
            $detallefactura->refresh();
            $detallefactura = $detallefactura->load('articulo');
            // Descuento Stock de Articulo
            $stock = Stock::where('idArticulo',$detallefactura->idArticulo);
            $stock->cantidad = $stock->cantidad - $detallefactura->cantidadArticulo;
            $stock->save();
            Log::info("Se actualizo el Stock para el Articulo: ".$stock->idArticulo." cantidad: ".$stock->cantidad);
            
        } catch (\Throwable $th) {
            Log::info("No se actualizo el Stock en Factura: id ->".$detallefactura->id." Numero: ".$detallefactura->idFactura);
            Log::error($th->getMessage());
        }
    }

    /**
     * Handle the Factura "updated" event.
     *
     * @param  \App\Models\Factura  $Factura
     * @return void
     */
    public function updated(Factura $Factura)
    {
        //
    }

    /**
     * Handle the Factura "deleted" event.
     *
     * @param  \App\Models\Factura  $Factura
     * @return void
     */
    public function deleted(Factura $Factura)
    {
        //
    }

    /**
     * Handle the Factura "restored" event.
     *
     * @param  \App\Models\Factura  $Factura
     * @return void
     */
    public function restored(Factura $Factura)
    {
        //
    }

    /**
     * Handle the Factura "force deleted" event.
     *
     * @param  \App\Models\Factura  $Factura
     * @return void
     */
    public function forceDeleted(Factura $Factura)
    {
        //
    }
}
