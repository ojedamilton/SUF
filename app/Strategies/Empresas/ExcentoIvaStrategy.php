<?php

namespace App\Strategies\Empresas;

use App\Interfaces\FacturacionInterface;

class ExcentoIvaStrategy implements FacturacionInterface {

    public function tipoFactura() {
        // Lógica para generar tipofactura C
        $tipoFactura = DB::table('tipoFacturas')->where('idTipoFactura',3)->first();
        return[
            "tipoFactura"=>$tipoFactura
        ];
    }
}