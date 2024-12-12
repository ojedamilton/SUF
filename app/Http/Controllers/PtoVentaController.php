<?php

namespace App\Http\Controllers;

use App\Models\PuntoVenta;
use Illuminate\Http\Request;

class PtoVentaController extends Controller
{
    public function index()
    {
        try {
            $ptoVenta = PuntoVenta::all();

            return response()->json([
                'success' => true,
                'message' => 'Listado de Puntos de Venta',
                'ptoVenta' => $ptoVenta
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Error al Listar Puntos de Venta',
                'ptoVenta' => null
            ], 500);
        }
    }
}
