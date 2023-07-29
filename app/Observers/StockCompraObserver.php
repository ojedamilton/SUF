<?php

namespace App\Observers;
use App\Models\Stock;
use App\Models\Articulo;
use App\Models\DetalleCompra;
use Illuminate\Support\Facades\Log;

use App\Models\Compra;

class StockCompraObserver
{
     /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(DetalleCompra $detallecompra)
    {
        // Logeo de Creacion Detalle Factura
        Log::info("Detalle Compra creado: id->".$detallecompra->id." NumeroCompra   ->".$detallecompra->idCompra." idArticulo->".$detallecompra->idArticulo." Cantidad->".$detallecompra->cantidadArticulo);
        try {
            // Refresco Instancia $factura recien creada
            $detallecompra->refresh();
            $detallecompra = $detallecompra->load('articulo');
            // Sumo Stock de Articulo
            $stock = Stock::where('idArticulo',$detallecompra->idArticulo)->first();
            $stock->cantidad = $stock->cantidad + $detallecompra->cantidadArticulo;
            $stock->save();
            Log::info("Se actualizo el Stock para el Articulo: ".$stock->idArticulo." cantidad: ".$stock->cantidad);
            
        } catch (\Throwable $th) {
            Log::info("No se actualizo el Stock en Compra: id ->".$detallecompra->id." Numero: ".$detallecompra->idCompra);
            Log::error($th->getMessage());
        }
    }
}
