<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Valor;
use Illuminate\Support\Facades\Auth;
use App\Repositories\MedioPagoRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ValorController extends Controller
{
    private $medioPagoRepository;
    
    public function __construct(MedioPagoRepository $medioPagoRepository){
     
      $this->medioPagoRepository = $medioPagoRepository;

    }

    /**
     * Display Listing of Pay - Method 
     * 
     * @return \Illuminate\Http\Response $valores
     */
    public function getAllValores(Request $request)
    {
    
        $buscar= $request->buscar;

        try {

            $medioPago=$this->medioPagoRepository->all($buscar,Auth::user()->idEmpresa);

            return response()->json([
                'success'=>true,
                'message'=>'Listado Medios de Pago',
                'valores'=>$medioPago,
                'pagination'=>[
                    'total'=>$medioPago->total(),
                    'current_page'=>$medioPago->currentPage(),
                    'per_page'=>$medioPago->perPage(),
                    'last_page'=>$medioPago->lastPage(),
                    'from'=>$medioPago->firstItem(),
                    'to'=>$medioPago->lastItem(),
                ],
            ],200);
        } catch (\Throwable $th) {

            return response()->json([
                'success'=>false,
                'message'=>'Error al Listar Medios de Pago',
                'valores'=>null,
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
        
            $medioPago= new Valor();
            $medioPago->nombreValor=$request->nombre;
            $medioPago->estadoValor=1;
            $medioPago->idEmpresa=Auth::user()->idEmpresa;
            $medioPago->save();

            DB::commit();

            return response()->json([
                "success"=>true,
                "message"=>"El Medio de pago se ha creado correctamente",
            ],201);

        } catch (\Throwable $th) {
            
            DB::rollback();
            
            Log::error("message:".$th->getMessage());

            return response()->json([
                "success"=>false,
                "message"=>"No se creo el Medio pago",
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
