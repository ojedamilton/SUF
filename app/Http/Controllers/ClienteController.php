<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Empresa;
use Illuminate\Support\Facades\Auth;
use App\Repositories\ClientRepository;
use App\Strategies\Clientes\ConsumidorFinalStrategy;
use App\Strategies\Clientes\ExcentoStrategy;
use App\Strategies\Clientes\MonotributistaStrategy;
use App\Strategies\Clientes\ResponsableInscriptoStrategy;

class ClienteController extends Controller
{
    private $clientRepository;
    
    public function __construct(ClientRepository $clientRepository){
     
      $this->clientRepository = $clientRepository;

    }
    public function getAllClientes(Request $request)
    {
        // Si quieren Ingresar sin un request , redirecciona al home
        if(!$request->ajax())return redirect('/home');

         $buscar= $request->buscar;

         $cliente=$this->clientRepository->all($buscar,Auth::user()->idEmpresa);

        $saludo=Cliente::saludar();

        return[
            'pagination'=>[
                'total'=>$cliente->total(),
                'current_page'=>$cliente->currentPage(),
                'per_page'=>$cliente->perPage(),
                'last_page'=>$cliente->lastPage(),
                'from'=>$cliente->firstItem(),
                'to'=>$cliente->lastItem(),
            ],
            'clientes'=>$cliente,
            'metodo'=>$saludo,
        ];
    }
    
    public function clienteTipoFactura(Request $request){  
        // Si es un Tipo de Empresa Responsable Inscripto   
        // Dependiendo situacion Fiscal aplico la estrategia con el tipo de factura
        $idEmpresaUser = Auth::user()->idEmpresa;
        // busco al tipo empresa
        $idTipoEmpresa = Empresa::where('id',$idEmpresaUser)->first();

        if ($idTipoEmpresa->idTipoEmpresa == 1) {
        
            switch ($request->idTipo) {
                case 1:
                    return (new ConsumidorFinalStrategy)->tipoFactura();
                    break;
                case 2:
                    return (new ExcentoStrategy)->tipoFactura();
                    break;
            }

        }
        return[
            "tipoFactura"=>false
        ];

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
