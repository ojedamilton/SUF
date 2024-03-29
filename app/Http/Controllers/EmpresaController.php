<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EmpresaController extends Controller
{
    public function getAllEmpresas(Request $request){
   
       
        $buscar= $request->buscar;
        
        try {
            if($buscar == 'userListado'){
                $empresas = Empresa::select('id','nombreEmpresa')
                    ->where('estadoEmpresa', 1)
                    ->paginate(15);
            }else{
            $empresas = Empresa::select('id','nombreEmpresa','razonSocial','cuitEmpresa','ingresosBrutosEmpresa','telEmpresa','direccionEmpresa','inicioActividades','idTipoEmpresa','estadoEmpresa')
                ->where(function ($query) use ($buscar) {
                    $query->where('nombreEmpresa', 'like', '%' . $buscar . '%')
                        ->orWhere('cuitEmpresa', 'like', '%' . $buscar . '%');
                })
                ->where('estadoEmpresa', 1)
                ->orderBy('nombreEmpresa', 'asc')
                ->paginate(15);
            }
            return response()->json([
                'success'=>true,
                'message'=>'Listado de Empresas',
                'empresas'=>$empresas,
                'pagination'=>[
                    'total'=>$empresas->total(),
                    'current_page'=>$empresas->currentPage(),
                    'per_page'=>$empresas->perPage(),
                    'last_page'=>$empresas->lastPage(),
                    'from'=>$empresas->firstItem(),
                    'to'=>$empresas->lastItem(),
                ],
            ],200);

        } catch (\Throwable $th) {  
            Log::error($th->getMessage());
            return response()->json([
                'success'=>false,
                'message'=>'Error al listar Empresas',
                'empresas'=>null,
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
        
    public function UserEmpresa(){
        
        $userEmpresa = Empresa::find(Auth::user()->idEmpresa);

        return [

            'userEmpresa'=>$userEmpresa
        ];
    }

    public function createEmpresa(Request $request){
         // Valido Backend
         $validator = Validator::make(
            $request->all(),[
            'nombre'=>'required|max:50',
            'razonsocial'=>'required',
            'cuitEmpresa'=>'required|unique:empresas',
            'ingresosbrutos'=>'required',
            'direccion'=>'required',
            'telefono'=>'required',
            'inicioActividades'=>'required',
            'tipoId'=>'required'
        ],[
            'razonsocial'=>'La razon social es requerida',
            'cuit.required'=>'El cuit es requerido',
            'cuit.unique'=>'El cuit ya existe',
            'nombre.required'=>'El nombre es requerido',
            'ingresosbrutos'=>'Ingresos Brutos es requerido',
            'inicioActividades'=>'El Inicio de Actividades es requerido',
            'direccion'=>'La direccion es requerida',
            'telefono'=>'El telefono es requerida',
            'tipoId'=>'El Tipo Empresa es requerido'
        ]);
        if ($validator->fails()) {
            return response()->json(["errors" => $validator->getMessageBag(),"status"=>401]);
        }
        //dd($request->cuitEmpresa);
        // Comienzo Transaccion
        DB::beginTransaction();
        try { 
            // Creo usuario en la tabla
            $empresa = new Empresa();
            $empresa->nombreEmpresa=$request->nombre;
            $empresa->razonSocial=$request->razonsocial;
            $empresa->cuitEmpresa=$request->cuitEmpresa;
            $empresa->ingresosBrutosEmpresa=$request->ingresosbrutos;
            $empresa->telEmpresa=$request->telefono;
            $empresa->direccionEmpresa=$request->direccion;
            $empresa->inicioActividades=$request->inicioActividades;
            $empresa->idTipoEmpresa=$request->tipoId;
            $empresa->estadoEmpresa=1;
            $empresa->save();

            DB::commit();
            return response()->json(["success"=>"La empresa se ha creado correctamente","status"=>201]);
        } catch (\Throwable $th) {
            DB::rollBack();
            // Logeo errores
            Log::error($th->getMessage());
            return response()->json(["errors"=>"No se pudo crear la empresa","status"=>401]);
        }
    }

    public function update(Request $request)
    {
        // Comienzo Transaccion
        DB::beginTransaction();

        try { 

            $empresa = Empresa::find($request->idEmpresa);
            $empresa->nombreEmpresa=$request->nombreEmpresa;
            $empresa->razonSocial=$request->razonSocial;
            $empresa->cuitEmpresa=$request->cuitEmpresa;
            $empresa->ingresosBrutosEmpresa=$request->ingresosBrutosEmpresa;
            $empresa->telEmpresa=$request->telEmpresa;
            $empresa->direccionEmpresa=$request->direccionEmpresa;
            $empresa->inicioActividades=$request->inicioActividades;
            $empresa->idTipoEmpresa=$request->idTipoEmpresa;
            $empresa->save();
            DB::commit();
            return response()->json([
                "success"=>true,
                "message"=>"La Empresa se ha actualizado correctamente",
                "empresa"=>$empresa
            ],200);

        } catch (\Throwable $th) {
            
            DB::rollBack();
            // Logeo errores
            Log::error($th->getMessage());
            return response()->json(["errors"=>"No se pudo actualizar la Empresa","status"=>500],500);
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
            $empresa = Empresa::find($request->idEmpresa);

            if ($empresa->id == 1) {
                return response()->json([
                    "success" => false,
                    "errors" => "No se puede eliminar esta empresa."
                ], 400);
            }


            $empresa->estadoEmpresa = 0;
            $empresa->save();
            DB::commit();
            return response()->json([
                "success"=>true,
                "message"=>"La Empresa se ha eliminado correctamente",
                "empresa"=>$empresa
            ],200);

        } catch (\Throwable $th) {
            DB::rollBack();
            // Logeo errores
            Log::error($th->getMessage());
            return response()->json([
                "success"=>false,
                "errors"=>"No se pudo eliminar la Empresa"
            ],500);
        }
    }
}
