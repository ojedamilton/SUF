<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ProveedorController extends Controller
{
    public function index(Request $request) {
        try {

            $buscar= $request->buscar;
            if (!$buscar) {
                $proveedor=Proveedor::orderBy('id','asc')->paginate(10);
            }else{
                $proveedor=Proveedor::where('nombreProveedor','like','%'.$buscar.'%')
                    ->orWhere('apellidoProveedor','like','%'.$buscar.'%')
                    ->orWhere('cuitProveedor','like','%'.$buscar.'%')
                    ->orderBy('apellidoProveedor','asc')
                    ->paginate(5);     
            }
              
            return response()->json([
                    'success'=>true,
                    'message'=>'Listado de proveedores',
                    'proveedores'=>$proveedor,
                    'pagination'=>[
                        'total'=>$proveedor->total(),
                        'current_page'=>$proveedor->currentPage(),
                        'per_page'=>$proveedor->perPage(),
                        'last_page'=>$proveedor->lastPage(),
                        'from'=>$proveedor->firstItem(),
                        'to'=>$proveedor->lastItem(),
                    ],
            ],200); 
            
        } catch (\Throwable $th) {

            Log::info($th->getMessage());

            return response()->json([
                'success'=>false,
                'message'=>'No se encontraron proveedors',
                'proveedores'=>null,
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
    /**
    * Store a newly created resource in Proveedor.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request) {
        // Comienzo Transaccion
        DB::beginTransaction();

        try {
            $proveedor = new Proveedor();
            $proveedor->nombreProveedor = $request->nombre;
            $proveedor->apellidoProveedor = $request->apellido;
            $proveedor->telefonoProveedor = $request->telefono;
            $proveedor->emailProveedor = $request->email;
            $proveedor->cuitProveedor = $request->cuit;
            $proveedor->direccionProveedor = $request->direccion;
            $proveedor->id_empresa = Auth::user()->idEmpresa;
            $proveedor->save();
            DB::commit();
            return response()->json([
                'success'=>true,
                'message'=>'Proveedor creado correctamente',
                'proveedor'=>$proveedor,
            ],201); 

        } catch (\Throwable $th) {
            DB::rollBack();
            Log::info($th->getMessage());

            return response()->json([
                'success'=>false,
                'message'=>'No se pudo crear el proveedor',
                'proveedor'=>null,
            ],500); 
        }
    }
     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request) {
        // Comienzo Transaccion
        DB::beginTransaction();
        try {
            $proveedor = Proveedor::find($request->idProveedor);
            $proveedor->nombreProveedor = $request->nombreProveedor;
            $proveedor->apellidoProveedor = $request->apellidoProveedor;
            $proveedor->telefonoProveedor = $request->telefonoProveedor;
            $proveedor->emailProveedor = $request->emailProveedor;
            $proveedor->cuitProveedor = $request->cuitProveedor;
            $proveedor->direccionProveedor = $request->direccionProveedor;
            $proveedor->save();
            DB::commit();
            return response()->json([
                'success'=>true,
                'message'=>'Proveedor actualizado correctamente',
                'proveedor'=>$proveedor,
            ],200); 

        } catch (\Throwable $th) {

            Log::info($th->getMessage());

            return response()->json([
                'success'=>false,
                'message'=>'No se pudo actualizar el proveedor',
                'proveedor'=>null,
            ],500); 
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request) {

         // Comienzo Transaccion
         DB::beginTransaction();

        try {

            $proveedor = Proveedor::find($request->idProveedor);
            $proveedor->estadoProveedor = 0;
            $proveedor->save();
            DB::commit();

            return response()->json([
                'success'=>true,
                'message'=>'Proveedor eliminado correctamente',
                'proveedor'=>$proveedor,
            ],200); 

        } catch (\Throwable $th) {
            DB::rollBack();
            // Logeo errores
            Log::error($th->getMessage());

            return response()->json([
                'success'=>false,
                'message'=>'No se pudo eliminar el proveedor',
                'proveedor'=>null,
            ],500); 
        }
    }
}
