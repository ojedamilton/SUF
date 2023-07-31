<?php

namespace App\Http\Controllers;

use App\Models\Accion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AccionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request  $request)
    {
        try {

            $buscar= $request->buscar;
            if (!$buscar) {
                $accion=Accion::select('id','nombreAccion','descripcionAccion')
                    ->orderBy('id','asc')
                    ->paginate(15);
            }else{
                $accion=Accion::select('id','nombreAccion','descripcionAccion')
                    ->where('nombreAccion','like','%'.$buscar.'%')
                    ->paginate(10);
            }
              
            return response()->json([
                    'success'=>true,
                    'message'=>'Listado de Acciones',
                    'acciones'=>$accion,
                    'pagination'=>[
                        'total'=>$accion->total(),
                        'current_page'=>$accion->currentPage(),
                        'per_page'=>$accion->perPage(),
                        'last_page'=>$accion->lastPage(),
                        'from'=>$accion->firstItem(),
                        'to'=>$accion->lastItem(),
                    ],
            ],200); 
            
        } catch (\Throwable $th) {

            Log::info($th->getMessage());

            return response()->json([
                'success'=>false,
                'message'=>'No se encontraron Acciones',
                'acciones'=>null,
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
     * @param  \App\Models\Accion  $accion
     * @return \Illuminate\Http\Response
     */
    public function show(Accion $accion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Accion  $accion
     * @return \Illuminate\Http\Response
     */
    public function edit(Accion $accion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Accion  $accion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Accion $accion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Accion  $accion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Accion $accion)
    {
        //
    }
}
