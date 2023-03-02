<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Valor;
use Illuminate\Support\Facades\Auth;
use App\Repositories\MedioPagoRepository;

class ValorController extends Controller
{
    private $medioPagoRepository;
    
    public function __construct(MedioPagoRepository $medioPagoRepository){
     
      $this->medioPagoRepository = $medioPagoRepository;

    }

    public function getAllValores(Request $request)
    {
        // Si quieren Ingresar sin un request , redirecciona al home 
        if(!$request->ajax())return redirect('/home');

        $buscar= $request->buscar;

        $medioPago=$this->medioPagoRepository->all($buscar,Auth::user()->idEmpresa);


        return[
            'pagination'=>[
                'total'=>$medioPago->total(),
                'current_page'=>$medioPago->currentPage(),
                'per_page'=>$medioPago->perPage(),
                'last_page'=>$medioPago->lastPage(),
                'from'=>$medioPago->firstItem(),
                'to'=>$medioPago->lastItem(),
            ],
            'valores'=>$medioPago,
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
