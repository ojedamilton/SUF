<?php

namespace App\Observers;
use App\Models\Stock;
use App\Models\Articulo;
use App\Models\DetalleNotaCredito;
use Illuminate\Support\Facades\Log;

use App\Models\NotaCredito;

class StockNotaCreditoObserver
{
     /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(DetalleNotaCredito $detallenotacredito)
    {
        // Logeo de Creacion Detalle Nota de Credito
        Log::info("Detalle Nota de Credito creado: id->".$detallenotacredito->id." NumeroNotaCredito   ->".$detallenotacredito->idNotaCredito." idArticulo->".$detallenotacredito->idArticulo." Cantidad->".$detallenotacredito->cantidadArticulo);
        try {
            // Refresco Instancia $notacredito recien creada
            $detallenotacredito->refresh();
            $detallenotacredito = $detallenotacredito->load('articulo');
            // Sumo Stock de Articulo
            $stock = Stock::where('idArticulo',$detallenotacredito->idArticulo)->first();
            $stock->cantidad = $stock->cantidad + $detallenotacredito->cantidadArticulo;
            $stock->save();
            Log::info("Se actualizo el Stock para el Articulo: ".$stock->idArticulo." cantidad: ".$stock->cantidad);
            
        } catch (\Throwable $th) {
            Log::info("No se actualizo el Stock en Nota de Credito: id ->".$detallenotacredito->id." Numero: ".$detallenotacredito->idNotaCredito);
            Log::error($th->getMessage());
        }
    }
}
