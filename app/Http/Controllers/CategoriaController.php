<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllCategoria(Request $request)
    {

        $buscar= $request->buscar;

            try {

                $categorias = Categoria::where('estadoCategoria',1)
                    ->where('nombreCategoria', 'LIKE', "%$buscar%")
                    ->orderBy('nombreCategoria', 'asc')
                    ->paginate(10);

                return response()->json([
                    'success'=>true,
                    'message'=>'Listado Categorias',
                    'categorias'=>$categorias,
                    'pagination'=>[
                        'total'=>$categorias->total(),
                        'current_page'=>$categorias->currentPage(),
                        'per_page'=>$categorias->perPage(),
                        'last_page'=>$categorias->lastPage(),
                        'from'=>$categorias->firstItem(),
                        'to'=>$categorias->lastItem(),
                    ],
                ],200);
            } catch (\Throwable $th) {
    
                return response()->json([
                    'success'=>false,
                    'message'=>'Error al Listar Categorias',
                    'categorias'=>null,
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
        
            $categorias= new Categoria();
            $categorias->nombreCategoria=$request->nombre;
            $categorias->estadoCategoria=1;
            $categorias->save();

            DB::commit();

            return response()->json([
                "success"=>true,
                "message"=>"La categoria se ha creado correctamente",
            ],201);

        } catch (\Throwable $th) {
            
            DB::rollback();
            
            Log::error("message:".$th->getMessage());

            return response()->json([
                "success"=>false,
                "message"=>"No se creo la categoria",
            ],500);
        }
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
    

    public function update(Request $request)
    {
        // Comienzo Transaccion
        DB::beginTransaction();

        try { 

            $categorias = Categoria::find($request->idCategoria);
            $categorias->nombreCategoria=$request->nombreCategoria;
            $categorias->save();
            DB::commit();
            return response()->json([
                "success"=>true,
                "message"=>"La categoiria se ha actualizado correctamente",
                "categorias"=>$categorias
            ],200);

        } catch (\Throwable $th) {
            
            DB::rollBack();
            // Logeo errores
            Log::error($th->getMessage());
            return response()->json(["errors"=>"No se pudo actualizar la categoria","status"=>500],500);
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
            $categorias = Categoria::find($request->idCategoria);
            $categorias->estadoCategoria = 0;
            $categorias->save();
            DB::commit();
            return response()->json([
                "success"=>true,
                "message"=>"La categoria se ha eliminado correctamente",
                "categorias"=>$categorias
            ],200);

        } catch (\Throwable $th) {
            DB::rollBack();
            // Logeo errores
            Log::error($th->getMessage());
            return response()->json([
                "success"=>false,
                "errors"=>"No se pudo eliminar la categoria"
            ],500);
        }
    }
}
