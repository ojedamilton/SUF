<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use Illuminate\Http\Request;

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

    public function getAllArticulos(Request $request) {
         //dd('llegue aca');
         // Si quieren Ingresar sin un request , redirecciona al home 
         if(!$request->ajax())return redirect('/');

         $buscar= $request->buscar;
        if (!$buscar) {
            $articulo=Articulo::orderBy('id','asc')->paginate(3);
        }else{
            $articulo=Articulo::where('nombreArticulo','like','%'.$buscar.'%')
                                ->orWhere('id','like','%'.$buscar.'%')
                                ->orderBy('nombreArticulo','asc')
                                ->paginate(3);     
        }
          
        return[
            'articulos'=>$articulo,
            'pagination'=>[
                'total'=>$articulo->total(),
                'current_page'=>$articulo->currentPage(),
                'per_page'=>$articulo->perPage(),
                'last_page'=>$articulo->lastPage(),
                'from'=>$articulo->firstItem(),
                'to'=>$articulo->lastItem(),
            ],
        ]; 
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
