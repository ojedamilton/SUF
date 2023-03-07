<?php

namespace App\Strategies\Empresas;

use App\Interfaces\FacturacionInterface;
use Illuminate\Support\Facades\DB;

class ResponsableInscriptoStrategy implements FacturacionInterface {
   
    public function tipoFactura() {
        // Lógica para retornar tipofactura A y B
        $tipoFactura = DB::table('tipoFacturas')->whereIn('idTipoFactura',[1,2])->orderBy('idTipoFactura','desc')->get();
        return[
            "tipoFactura"=>$tipoFactura
        ];
    }
}