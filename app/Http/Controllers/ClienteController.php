<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Factura;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
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
        $buscar= $request->buscar;

        try {

            $cliente=$this->clientRepository->all($buscar,Auth::user()->idEmpresa);


            return response()->json([
                'success'=>false,
                'message'=>'Listado de Clientes',
                'clientes'=>$cliente,
                'pagination'=>[
                    'total'=>$cliente->total(),
                    'current_page'=>$cliente->currentPage(),
                    'per_page'=>$cliente->perPage(),
                    'last_page'=>$cliente->lastPage(),
                    'from'=>$cliente->firstItem(),
                    'to'=>$cliente->lastItem(),
                ],
            ],200);
            
        } catch (\Throwable $th) {

            Log::info($th->getMessage());

            return response()->json([
                'success'=>false,
                'message'=>'No se encontraron Clientes',
                'clientes'=>null,
                'pagination'=>[
                    'total'=>1,
                    'current_page'=>1,
                    'per_page'=>1,
                    'last_page'=>1,
                    'from'=>1,
                    'to'=>1,
                ],
            ],500); 
        }
       

        
    }


    public function getClienteByFactura(Request $request)
    {
        // Si quieren ingresar sin un request, redirecciona al home 
        if (!$request->ajax()) {
            return redirect('/');
        }
    
        $idFactura = $request->id;
        $factura = Factura::with('cliente.situacion')->find($idFactura);
    
        // Si no se encuentra la factura, retornar un mensaje de error o lo que consideres apropiado
        if (!$factura) {
            return response()->json([
                'success' => false,
                'message' => 'Factura no encontrada',
            ], 404);
        }
    
        // Obtener el cliente asociado a la factura
        $cliente = $factura->cliente;
    
        return response()->json([
            'success' => true,
            'message' => 'Cliente encontrado',
            'cliente' => $cliente,
        ], 200);
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
        return response()->json([
            "tipoFactura"=>false
        ]);

    }

    /**
     * Store a newly created resource in Client.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function crearCliente(Request $request)
    {
         // Valido Backend
         $validator = Validator::make(
            $request->all(),
            [
                'nombre'=>'required|max:50',
                'apellido'=>'required|max:50',
                'razonSocial' => $request->situacionId != 1 ? 'required' : '',
                'dniCliente'=>'required|unique:clientes',
                'direccion'=>'required',
                'email'=>'required|email',
                'telefono'=>'required',
                'situacionId'=>'required'
            ],[
                'razonsocial.required'=>'La razon social es requerida',
                'dniCliente.required'=>'El cuit es requerido',
                'dniCliente.unique'=>'El cuit ya existe',
                'nombre.required'=>'El nombre es requerido',
                'email.required'=>'El email es requerido',
                'email.email'=>'El email tiene formato erroneo',
                'apellido.required'=>'El apellido es requerido',
                'situacionId'=>'Situacion Fiscal es requerido',
                'direccion.required'=>'La direccion es requerida',
                'telefono.required'=>'El telefono es requerido',
            ]
        );

        if ($validator->fails()) {
            return response()->json(["errors" => $validator->getMessageBag(),"status"=>401]);
        }

        // Comienzo Transaccion
        DB::beginTransaction();
        
        try { 
            // Creo usuario en la tabla
            $cliente = new Cliente();
            $cliente->nombreCliente=$request->nombre;
            $cliente->apellidoCliente=$request->apellido;
            $cliente->razonSocial=$request->razonSocial;
            $cliente->emailCliente=$request->email;
            $cliente->idSituacion=$request->situacionId;
            $cliente->dniCliente=$request->dniCliente;
            $cliente->telefonoCliente=$request->telefono;
            $cliente->direccionCliente=$request->direccion;
            $cliente->idEmpresa=Auth::user()->idEmpresa;
            $cliente->estadoCliente=1;
            $cliente->save();

            DB::commit();
            return response()->json(["success"=>"El Cliente se ha creado correctamente","status"=>201]);
        } catch (\Throwable $th) {
            DB::rollBack();
            // Logeo errores
            Log::error($th->getMessage());
            return response()->json(["errors"=>"No se pudo crear el Cliente","status"=>500]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Comienzo Transaccion
        DB::beginTransaction();
    
        try { 
            // Valido Backend
            $validator = Validator::make(
                $request->all(),
                [
                    'nombreCliente' => 'required|max:50',
                    'apellidoCliente' => 'required|max:50',
                    'razonSocial' => $request->idSituacion != 1 ? 'required' : '',
                    'cuitCliente' => 'required|unique:clientes,dniCliente,' . $request->idCliente,
                    'direccionCliente' => 'required',
                    'emailCliente' => 'required|email',
                    'telefonoCliente' => 'required',
                    'idSituacion' => 'required'
                ],
                [
                    'razonSocial.required' => 'La razón social es requerida',
                    'cuitCliente.required' => 'El cuit es requerido',
                    'cuitCliente.unique' => 'El cuit ya existe',
                    'nombreCliente.required' => 'El nombre es requerido',
                    'emailCliente.required' => 'El email es requerido',
                    'emailCliente.email' => 'El email tiene formato erróneo',
                    'apellidoCliente.required' => 'El apellido es requerido',
                    'idSituacion.required' => 'Situación Fiscal es requerida',
                    'direccionCliente.required' => 'La dirección es requerida',
                    'telefonoCliente.required' => 'El teléfono es requerido',
                ]
            );
    
            if ($validator->fails()) {
                return response()->json(["errors" => $validator->getMessageBag(), "status" => 401]);
            }
    
            $cliente = Cliente::find($request->idCliente);
            $cliente->nombreCliente = $request->nombreCliente;
            $cliente->apellidoCliente = $request->apellidoCliente;
            $cliente->emailCliente = $request->emailCliente;
            $cliente->dniCliente = $request->cuitCliente;
            $cliente->telefonoCliente = intval($request->telefonoCliente);
            $cliente->direccionCliente = $request->direccionCliente;
            $cliente->razonSocial = $request->razonSocial;
            $cliente->idSituacion = $request->idSituacion;
            $cliente->save();
            DB::commit();
            return response()->json([
                "success" => true,
                "message" => "El Cliente se ha actualizado correctamente",
                "cliente" => $cliente
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            // Logeo errores
            Log::error($th->getMessage());
            return response()->json(["errors" => "No se pudo actualizar el Cliente", "status" => 500], 500);
        }
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // Comienzo Transaccion
        DB::beginTransaction();

        try { 
            $cliente = Cliente::find($request->idCliente);
            $cliente->estadoCliente = 0;
            $cliente->save();
            DB::commit();
            return response()->json([
                "success"=>true,
                "message"=>"El Cliente se ha eliminado correctamente",
                "cliente"=>$cliente
            ],200);

        } catch (\Throwable $th) {
            DB::rollBack();
            // Logeo errores
            Log::error($th->getMessage());
            return response()->json([
                "success"=>false,
                "errors"=>"No se pudo eliminar el Cliente"
            ],500);
        }
    }
}
