<?php

namespace App\Strategies\Empresas;

use App\Interfaces\FacturacionInterface;
use Illuminate\Support\Facades\DB;

class ResponsableInscriptoStrategy implements FacturacionInterface {
   
    public function tipoFactura() {
        try {
            // Lógica para generar tipofactura A y B
            $tipoFactura = DB::table('tipoFacturas')->whereIn('idTipoFactura',[1,2])->orderBy('idTipoFactura','desc')->get();
            return response()->json([
                "success"=>true,
                "message"=>"Tipo de Factura A-B segun Empresa",
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