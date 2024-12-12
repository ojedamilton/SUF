<?php

namespace App\Strategies\Clientes;

use App\Interfaces\FacturacionInterface;
use Illuminate\Support\Facades\DB;

class ResponsableInscriptoStrategy implements FacturacionInterface {
   
    public function tipoComprobante() {
        // Lógica para retornar tipofactura A
        $tipoFactura = DB::table('tipoFacturas')->where('idTipoFactura',1)->first();
        return[
            "tipoFactura"=>$tipoFactura
        ];
    }
}