<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Valor;
class ValorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllValores(Request $request)
    {
        // Si quieren Ingresar sin un request , redirecciona al home 
        if(!$request->ajax())return redirect('/home');

        $buscar= $request->buscar;

        $valores=Valor::where('nombreValor','like','%'.$buscar.'%')
                        ->orderBy('nombreValor','asc')
                        ->paginate(10);


        return[
            'pagination'=>[
                'total'=>$valores->total(),
                'current_page'=>$valores->currentPage(),
                'per_page'=>$valores->perPage(),
                'last_page'=>$valores->lastPage(),
                'from'=>$valores->firstItem(),
                'to'=>$valores->lastItem(),
            ],
            'valores'=>$valores,
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
