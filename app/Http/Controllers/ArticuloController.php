<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ArticuloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Display a listing of the articles.
     * 
     * @param  \Illuminate\Http\Request  $buscar
     * @return \Illuminate\Http\Response $articulos
     */
    public function getAllArticulos(Request $request) {

        try {

            $buscar= $request->buscar;
            if (!$buscar) {
                $articulo=Articulo::orderBy('id','asc')->paginate(5);
            }else{
                $articulo=Articulo::where('nombreArticulo','like','%'.$buscar.'%')
                                    ->orWhere('id','like','%'.$buscar.'%')
                                    ->orderBy('nombreArticulo','asc')
                                    ->paginate(5);     
            }
              
            return response()->json([
                    'success'=>true,
                    'message'=>'Listado de Articulos',
                    'articulos'=>$articulo,
                    'pagination'=>[
                        'total'=>$articulo->total(),
                        'current_page'=>$articulo->currentPage(),
                        'per_page'=>$articulo->perPage(),
                        'last_page'=>$articulo->lastPage(),
                        'from'=>$articulo->firstItem(),
                        'to'=>$articulo->lastItem(),
                    ],
            ],200); 
            
        } catch (\Throwable $th) {

            Log::info($th->getMessage());

            return response()->json([
                'success'=>false,
                'message'=>'No se encontraron Articulos',
                'articulos'=>null,
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
     * @param  \App\Models\Articulo  $articulo
     * @return \Illuminate\Http\Response
     */
    public function show(Articulo $articulo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Articulo  $articulo
     * @return \Illuminate\Http\Response
     */
    public function edit(Articulo $articulo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Articulo  $articulo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Articulo $articulo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Articulo  $articulo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Articulo $articulo)
    {
        //
    }
}
