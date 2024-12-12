<?php

namespace App\Strategies\Clientes;

use Illuminate\Support\Facades\DB;
use App\Interfaces\FacturacionInterface;

class ConsumidorFinalStrategy implements FacturacionInterface {

    public function tipoComprobante() {
        // Lógica para generar tipofactura B
        $tipoFactura = DB::table('tipoFacturas')->where('idTipoFactura',2)->first();
        return[
            "tipoFactura"=>$tipoFactura
        ];
    }
}