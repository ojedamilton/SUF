<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoEmpresa;
use App\Models\Empresa;
use Illuminate\Support\Facades\Auth;
use App\Strategies\Empresas\ResponsableInscriptoStrategy;
use App\Strategies\Empresas\MonotributistaStrategy;
use App\Strategies\Empresas\ExcentoIvaStrategy;
use Illuminate\Support\Facades\Log;

class TipoEmpresaController extends Controller
{
    /**
     * Display a listing of the Factory type.
     *
     * @return \Illuminate\Http\Response $tiposempresas
     */
    public function getAllTipoEmpresa(Request $request)
    {
        try {

            $tiposempresas=TipoEmpresa::all();

            return response()->json([
                "succes"=>true,
                "message"=>"Listado Tipo de Empresas",
                "tiposempresas"=>$tiposempresas,
            ]);

        } catch (\Throwable $th) {
            
            return response()->json([
                "succes"=>false,
                "message"=>"No se han encontrado Tipos de Empresas",
                "tiposempresas"=>null,
            ]);
        }
        
    }

    /**
     * Obtengo el tipo de factura
     * 
     * @param int tipoEmpresa
     * @return string tipoFactura 
     */
    public function getTipoFacturaEmpresa(){

        try {
            // Traigo empresa que pertenece
            $idEmpresaUser = Auth::user()->idEmpresa;
            // busco al tipo empresa
            $idTipoEmpresa = Empresa::where('id',$idEmpresaUser)->first();
            // dependiendo tipo empresa aplico la estrategia con el tipo de factura
            switch ($idTipoEmpresa->idTipoEmpresa) {
                case 1:
                    return (new ResponsableInscriptoStrategy)->tipoFactura();
                    break;
                case 2:
                    return (new ExcentoIvaStrategy)->tipoFactura();
                    break;
                case 3:
                    return (new MonotributistaStrategy)->tipoFactura();
                    break;    
                default:
                    return (new MonotributistaStrategy)->tipoFactura();
                    break;
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                "succes"=>false,
                "message"=>"No se ha encontrado el Tipo de Empresa",
                "tipoFactura"=>null,
            ],500);
        }
           
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
