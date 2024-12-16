<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Factura;
use App\Models\NotaCredito;
use App\Models\PuntoVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\DetalleFactura;
use App\Models\DetalleNotaCredito;
use App\Models\Stock;
use App\Repositories\FacturaRepository;
use Dompdf\Dompdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FacturaController extends Controller
{
    protected $facturaRepository;

    public function __construct(FacturaRepository $facturaRepository)
    {
        $this->facturaRepository = $facturaRepository;    
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllFacturas(Request $request)
    {

        try {
            $buscar= $request->buscar;
            
            $listadofacturas = $this->facturaRepository->all($buscar);

            return response()->json([
                'success' => true,
                'message' => 'Listado de Facturas',
                'listadofacturas' => $listadofacturas,
            ], 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al Listar Facturas',
                'listadofacturas' => null,
            ], 500);
        }
    }

    /**
     * Validar Campos del Formulario
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function validarForm(Request $request)
    {
        // Valido Backend
        $validator = Validator::make(
            $request->all(),
            [
                'factura.totalFactura.required' => 'El campo "Precio" es obligatorio.',
                'factura.id_cliente.integer' => 'Seleccione un Cliente',
                'factura.fecha.required' => 'La fecha es requerida',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error En la Validacion al Crear Factura',
                'factura' => null,
                'detalle' => null,
                'errors' => $validator->errors()
            ], 500);
        }
    }

    public function validarFacturaAsociada(Request $request)
    {
        $factura = Factura::where('numeroFactura', $request->FacturaAsociada)
            ->where('idEmpresa', Auth::user()->idEmpresa)
            ->first();

        if (!$factura) {
            return response()->json([
                'success' => false,
                'message' => '* Factura no encontrada',
                'factura' => null,
                'detalle' => null,
            ], 404);
        }
        
        if ($factura->totalFactura != $request->totalNotaCredito) {
            return response()->json([
                'success' => false,
                'message' => '* El total de la NC es distinto al de la factura asociada',
                'factura' => null,
                'detalle' => null,
            ], 500);
        }

        if ($factura->idTipoFactura != $request->tipoFacturaId) {
            return response()->json([
                'success' => false,
                'message' => '* El tipo de factura de la NC es distinto al de la factura asociada',
                'factura' => null,
                'detalle' => null,
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Factura Asociada Encontrada',
            'factura' => $factura,
            'detalle' => null,
        ], 200);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Obtengo Pto Venta
        $ptoVenta = PuntoVenta::first()->id;

        // Obtener la empresa del usuario logueado
        $idEmpresa = Auth::user()->idEmpresa;

        $factura = Factura::where('idTipoFactura', $request->factura['tipoFacturaId'])
                            ->where('idEmpresa', $idEmpresa) // Agregar filtro por empresa
                            ->first();
        // ultimo Numero por cada Tipo de Factura 
        if ($factura) {
            $ultimoNum = Factura::selectRaw("id,CONCAT(LPAD(numeroFactura+1, 6, '0')) as numeroFactura")
                ->where("idTipoFactura", $request->factura['tipoFacturaId'])
                ->where("idEmpresa", $idEmpresa) // Agregar filtro por empresa
                ->orderBy("numeroFactura", "desc")
                ->pluck('numeroFactura')
                ->first();
        } else {
            $ultimoNum = '000001';
        }
        
        try {

            // Llamo al metodo estatico de la clase Stock
            $validacion = Stock::consultarDisponibilidad($request->detalles);

            if (!$validacion['exito']) {
                throw new \Exception("No hay stock suficiente para el artículo: " . $validacion['articulo']);
            }
            
            // Comienzo Transaccion
            DB::beginTransaction();
            // Instancio Factura
            $factura = new Factura;
            $factura->numeroFactura = $ultimoNum;
            $factura->fechaModificacion = $request->factura['fecha'];
            $factura->estadoFactura = 1;
            $factura->idCliente = $request->factura['id_cliente'];
            $factura->idValor = $request->factura['pago'];
            $factura->idUsuario = Auth::user()->id;
            $factura->idTipoFactura = $request->factura['tipoFacturaId'];
            $factura->idEmpresa = Auth::user()->idEmpresa;
            $factura->idpuntoVenta = $ptoVenta;
            $factura->totalFactura = $request->factura['totalFactura'];
            $factura->descuento = $request->factura['descuento'];
            $factura->save();

            // Instancio Detalles
            $detalleReq = $request->detalles;
            // Agrego Id Factura a cada detalle y elimino el nombre
            foreach ($detalleReq as $key=>$detalle) {
                $detalleReq[$key]['idFactura'] = $factura->id;
                unset($detalleReq[$key]['nombre']);
            }

            // Creo los detalles
            $factura->detallesfactura()->createMany($detalleReq);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Factura Creada Correctamente',
                'factura' => $factura->id,
                'detalle' => $detalleReq,
            ], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al Crear Factura',
                'factura' => null,
                'detalle' => null,
            ], 500);
        }
    }

   
    public function getFacturasById(Request $request){
    
        // Si quieren Ingresar sin un request , redirecciona al home 
        if(!$request->ajax())return redirect('/');

        $factura=Factura::find($request->id);
        $factura= Factura::with('puntoventa','tipofactura')->selectRaw("id,descuento,totalFactura,fechaModificacion,totalFactura,idpuntoVenta,idTipoFactura, CONCAT(LPAD(numeroFactura, 6, '0')) as numeroFactura")
                                ->where('id',$request->id)
                                ->first();

       return[
           'factura'=>$factura,
       ]; 
   
} 
    public function  getDetallesById(Request $request){
       
        // Si quieren Ingresar sin un request , redirecciona al home 
        if(!$request->ajax())return redirect('/');

        
        $idFactura=$request->id;
        $detallesById=DetalleFactura::with('articulo')
            ->where('idFactura',$idFactura)
            ->get();

       return[
           'detallesbyid'=>$detallesById,
       ]; 
   
    }


    public function descargarFactura(Request $request)
    {
        // Obtener el contenido del modal
        $contenido = $request->contenido;

        // Crear un objeto Dompdf y renderizar el contenido del modal como un PDF
        $dompdf = new Dompdf();
        $dompdf->loadHtml($contenido);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        // Descargar el PDF
        return $dompdf->stream('contenido-modal.pdf');
    }

    public function reporteventas()
    {
        $fecha_actual = Carbon::now();
        $inicio_mes_actual = Carbon::now()->startOfMonth();
    
        // Totales de facturas
        $total_facturada_mes_actual = Factura::where('idEmpresa', Auth::user()->idEmpresa)
            ->whereBetween('fechaModificacion', [$inicio_mes_actual->toDateString(), $fecha_actual->toDateString()])
            ->sum('totalFactura');
    
        $cantidad_facturas = Factura::where('idEmpresa', Auth::user()->idEmpresa)
            ->whereBetween('fechaModificacion', [$inicio_mes_actual->toDateString(), $fecha_actual->toDateString()])
            ->count();
    
        $cantidad_articulos_facturados = Factura::where('idEmpresa', Auth::user()->idEmpresa)
            ->whereBetween('fechaModificacion', [$inicio_mes_actual->toDateString(), $fecha_actual->toDateString()])
            ->join('detallesfacturas', 'facturas.id', '=', 'detallesfacturas.idFactura')
            ->sum('detallesfacturas.cantidadArticulo');
    
        // Totales de notas de crédito
        $total_notas_credito_mes_actual = NotaCredito::where('idEmpresa', Auth::user()->idEmpresa)
            ->whereBetween('fechaNotaCredito', [$inicio_mes_actual->toDateString(), $fecha_actual->toDateString()])
            ->sum('totalNotaCredito');
    
        $cantidad_notas_credito = NotaCredito::where('idEmpresa', Auth::user()->idEmpresa)
            ->whereBetween('fechaNotaCredito', [$inicio_mes_actual->toDateString(), $fecha_actual->toDateString()])
            ->count();
    
        $cantidad_articulos_notas_credito = NotaCredito::where('idEmpresa', Auth::user()->idEmpresa)
            ->whereBetween('fechaNotaCredito', [$inicio_mes_actual->toDateString(), $fecha_actual->toDateString()])
            ->join('detallesnotascredito', 'notascredito.id', '=', 'detallesnotascredito.idNotaCredito')
            ->sum('detallesnotascredito.cantidadArticulo');
    
        // Ajustar las métricas totales restando las notas de crédito
        $total_ventas_ajustado = $total_facturada_mes_actual - $total_notas_credito_mes_actual;
        $cantidad_articulos_ajustado = $cantidad_articulos_facturados - $cantidad_articulos_notas_credito;
    
        // Métricas para el gráfico de barras
        $numeroMes = $fecha_actual->month;
        $nameMeses = [];
        $amount = [];
        $countFacturas = [];
        $countArticulos = [];
    
        for ($i = 1; $i <= $numeroMes; $i++) {
            $currentMonth = $fecha_actual->month($i);
            $nameofMonth = $currentMonth->formatLocalized('%B');
            array_push($nameMeses, $nameofMonth);
    
            $startOfMonth = $currentMonth->startOfMonth()->toDateString();
            $endOfMonth = date("Y-m-t", strtotime($currentMonth->toDateString()));
    
            // Facturas mensuales
            $total_facturas_mes = Factura::where('idEmpresa', Auth::user()->idEmpresa)
                ->whereBetween('fechaModificacion', [$startOfMonth, $endOfMonth])
                ->sum('totalFactura');
    
            // Notas de crédito mensuales
            $total_notas_credito_mes = NotaCredito::where('idEmpresa', Auth::user()->idEmpresa)
                ->whereBetween('fechaNotaCredito', [$startOfMonth, $endOfMonth])
                ->sum('totalNotaCredito');
    
            // Ajustar ventas por mes
            $ventas_ajustadas_mes = $total_facturas_mes - $total_notas_credito_mes;
            array_push($amount, $ventas_ajustadas_mes);
    
            $cantidad_facturas_mes = Factura::where('idEmpresa', Auth::user()->idEmpresa)
                ->whereBetween('fechaModificacion', [$startOfMonth, $endOfMonth])
                ->count();
    
            array_push($countFacturas, $cantidad_facturas_mes);
    
            $cantidad_articulos_facturados_mes = Factura::where('idEmpresa', Auth::user()->idEmpresa)
                ->whereBetween('fechaModificacion', [$startOfMonth, $endOfMonth])
                ->join('detallesfacturas', 'facturas.id', '=', 'detallesfacturas.idFactura')
                ->sum('detallesfacturas.cantidadArticulo');
    
            $cantidad_articulos_notas_mes = NotaCredito::where('idEmpresa', Auth::user()->idEmpresa)
                ->whereBetween('fechaNotaCredito', [$startOfMonth, $endOfMonth])
                ->join('detallesnotascredito', 'notascredito.id', '=', 'detallesnotascredito.idNotaCredito')
                ->sum('detallesnotascredito.cantidadArticulo');
    
            $articulos_ajustados_mes = $cantidad_articulos_facturados_mes - $cantidad_articulos_notas_mes;
            array_push($countArticulos, $articulos_ajustados_mes);
        }
    
        // Métricas para el gráfico de pie
        $articulos_facturados = DetalleFactura::select('a.nombreArticulo', DB::raw('SUM(detallesfacturas.cantidadArticulo) as total'))
            ->join('facturas as f', 'detallesfacturas.idFactura', '=', 'f.id')
            ->join('articulos as a', 'detallesfacturas.idArticulo', '=', 'a.id')
            ->where('f.idEmpresa', Auth::user()->idEmpresa)
            ->whereBetween('f.fechaModificacion', [$startOfMonth, $endOfMonth])
            ->groupBy('detallesfacturas.idArticulo', 'a.id', 'a.nombreArticulo')
            ->pluck('total', 'nombreArticulo');

        $articulos_notas_credito = DetalleNotaCredito::select('a.nombreArticulo', DB::raw('SUM(detallesnotascredito.cantidadArticulo) as total'))
            ->join('notascredito as nc', 'detallesnotascredito.idNotaCredito', '=', 'nc.id')
            ->join('articulos as a', 'detallesnotascredito.idArticulo', '=', 'a.id')
            ->where('nc.idEmpresa', Auth::user()->idEmpresa)
            ->whereBetween('nc.fechaNotaCredito', [$startOfMonth, $endOfMonth])
            ->groupBy('detallesnotascredito.idArticulo', 'a.id', 'a.nombreArticulo')
            ->pluck('total', 'nombreArticulo');

        // Combinar los datos de facturas y notas de crédito para calcular los totales ajustados
        $cantidadArticulos = $articulos_facturados->map(function ($total, $articulo) use ($articulos_notas_credito) {
            return $total - ($articulos_notas_credito[$articulo] ?? 0);
        })->filter(function ($total) {
            return $total > 0; // Eliminar artículos con valores negativos o cero
        });

    
        return response()->json([
            "cantVentaMensual" => $cantidad_facturas - $cantidad_notas_credito,
            "totalVentaMensual" => $total_ventas_ajustado,
            "cantidadArticulosMensual" => $cantidad_articulos_ajustado,
            "ArtMasVendido" => 'Sin Articulos',
            "toCurrentMonths" => $nameMeses,
            "amount" => $amount,
            "countFacturas" => $countFacturas,
            "countArticulos" => $countArticulos,
            'cantidadArticulos' => $cantidadArticulos,
        ], 200);
    }    

}
