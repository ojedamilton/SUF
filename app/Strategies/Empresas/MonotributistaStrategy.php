<?php

namespace App\Strategies\Empresas;

use App\Interfaces\FacturacionInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MonotributistaStrategy implements FacturacionInterface {

    public function tipoFactura() {
        try {
            // Lógica para generar tipofactura C
            $tipoFactura = DB::table('tipoFacturas')->where('idTipoFactura',3)->get();
            return response()->json([
                "success"=>true,
                "message"=>"Tipo de Factura segun Empresa",
                "tipoFactura"=>$tipoFactura,
            ],200);

        } catch (\Throwable $th) {
            Log::error("error:".$th->getMessage());
            return response()->json([
                "success"=>false,
                "message"=>"No se encontraron Tipo de Factura",
                "tipoFactura"=>null,
            ],500);
        }
        
    }
}