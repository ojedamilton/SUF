<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllClientes(Request $request)
    {
        // Si quieren Ingresar sin un request , redirecciona al home
        if(!$request->ajax())return redirect('/home');

         $buscar= $request->buscar;

         $cliente=Cliente::where('nombreCliente','like','%'.$buscar.'%')
                        ->orWhere('apellidoCliente','like','%'.$buscar.'%')
                        ->orWhere('dniCliente','like','%'.$buscar.'%')
                        ->orderBy('nombreCliente','asc')
                        ->paginate(10);

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
