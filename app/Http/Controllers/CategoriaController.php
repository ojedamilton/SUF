<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllCategoria(Request $request)
    {


            try {

                $categorias=Categoria::all();

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
    public function create()
    {
        //
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
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function show(Categoria $categoria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function edit(Categoria $categoria)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Categoria $categoria)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function destroy(Categoria $categoria)
    {
        //
    }
}
