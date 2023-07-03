<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($page, $buscar)
    {
        try {
            $page_url = $page;
            $buscar_url = $buscar;
            //$buscar= $request->buscar;
            if ($buscar == '*') {
                $stocks=Stock::with('articulo','proveedor')
                    ->orderBy('id','asc')->paginate(10);
            }else{
                /* $stocks = Stock::with('articulo', 'proveedor')
                ->whereHas('articulo', function ($query) use ($buscar) {
                    $query->where('nombreArticulo', 'like', '%' . $buscar . '%')
                    ->orWhereHas('proveedor', function ($subquery) use ($buscar) {
                        $subquery->where('nombreProveedor', 'like', '%' . $buscar . '%');
                    });
                }) */
                $stock = Stock::whereRelation('articulo', 'nombreArticulo', 'like', '%' . $buscar . '%')
                            /* ->orWhereRelation('proveedor', 'nombreProveedor', 'like', '%' . $buscar . '%') */
                ->paginate(5);     
            }
              
            return response()->json([
                    'success'=>true,
                    'message'=>'Listado de inventario Stock',
                    'stocks'=>$stocks,
                    'pagination'=>[
                        'total'=>$stocks->total(),
                        'current_page'=>$stocks->currentPage(),
                        'per_page'=>$stocks->perPage(),
                        'last_page'=>$stocks->lastPage(),
                        'from'=>$stocks->firstItem(),
                        'to'=>$stocks->lastItem(),
                    ],
            ],200); 
            
        } catch (\Throwable $th) {

            Log::info($th->getMessage());

            return response()->json([
                'success'=>false,
                'message'=>'No se encontraron Inventario Stock',
                'proveedores'=>null,
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
    public function update(Request $request)
    {
        // Comienzo Transaccion
        DB::beginTransaction();
        try {
            $stock = Stock::find($request->idStock);
            $stock->cantidad = $request->cantidad;
            $stock->cantidadMinima = $request->cantidadMinima;
            $stock->save();
            DB::commit();
            return response()->json([
                'success'=>true,
                'message'=>'stock actualizado correctamente',
                'stock'=>$stock,
            ],200); 

        } catch (\Throwable $th) {

            Log::info($th->getMessage());

            return response()->json([
                'success'=>false,
                'message'=>'No se pudo actualizar el stock',
                'stock'=>null,
            ],500); 
        }
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
