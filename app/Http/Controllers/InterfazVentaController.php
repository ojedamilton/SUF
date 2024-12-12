<?php

namespace App\Http\Controllers;

use App\Models\InterfazVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class InterfazVentaController extends Controller
{
    public function interfazVentaByEmpresa(Request $request){

        $buscar= $request->buscar;

        try {
            $InterfazVenta = InterfazVenta::where('id_empresa', Auth::user()->idEmpresa)
                ->first();
            return response()->json([
                'success' => true,
                'message' => 'Interfaz de Venta obtenido con éxito',
                'interfazVenta' => $InterfazVenta,
            ], 200);
        } catch (\Throwable $th) {
            Log::error('InterfazVentaController@index -> '.$th->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener Interfaz de Venta',
                'interfaz' => null,
                'pagination'=>[
                    'total'=>1,
                    'current_page'=>1,
                    'per_page'=>1,
                    'last_page'=>1,
                    'from'=>1,
                    'to'=>1,
                ],
            ], 500);
        }
    }
    public function store (Request $request){

        try {
            $InterfazVenta = InterfazVenta::find($request->idInterfazVenta);
            $InterfazVenta->nro_local = $request->nroLocal;
            $InterfazVenta->nro_contrato = $request->nroContrato;
            $InterfazVenta->pos_local = $request->posLocal;
            $InterfazVenta->id_pto_vta = $request->idPtoVta;
            $InterfazVenta->ruta = $request->ruta;
            $InterfazVenta->id_empresa = Auth::user()->idEmpresa;
            $InterfazVenta->save();
            return response()->json([
                'success' => true,
                'message' => 'Interfaz de Venta creado con éxito',
                'interfazVenta' => $InterfazVenta
            ], 200);
        } catch (\Throwable $th) {
            Log::error('InterfazVentaController@store -> '.$th);
            return response()->json([
                'success' => false,
                'message' => 'Error al crear Interfaz de Venta',
                'ptoVenta' => null
            ], 500);
        }
    }
}
