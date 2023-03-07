<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoEmpresa;
use App\Models\Empresa;
use Illuminate\Support\Facades\Auth;
use App\Strategies\Empresas\ResponsableInscriptoStrategy;
use App\Strategies\Empresas\MonotributistaStrategy;
use App\Strategies\Empresas\ExcentoIvaStrategy;

class TipoEmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllTipoEmpresa(Request $request)
    {
        // Si quieren Ingresar sin un request , redirecciona al home
        if(!$request->ajax())return redirect('/home');

        $tiposempresas=TipoEmpresa::all();  //query que le pega al modelo

        return[
            'tiposempresas'=>$tiposempresas,
        ];
    }
    /**
     * Obtengo el tipo de factura
     * @param int tipoEmpresa @param cliente 
     */
    public function getTipoFacturaEmpresa(){
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
