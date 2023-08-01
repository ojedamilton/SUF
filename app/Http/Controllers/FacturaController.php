<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Factura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\DetalleFactura;
use App\Models\Stock;
use Dompdf\Dompdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FacturaController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllFacturas(Request $request)
    {

        try {
            $buscar= $request->buscar;

            
            $query = Factura::with('detallesfactura','puntoventa', 'detallesfactura.articulo','detallesfactura.articulo.stock')
                ->select('facturas.id','facturas.idpuntoVenta','facturas.numeroFactura','facturas.totalFactura','facturas.fechaModificacion','tipofacturas.tipoFactura','users.name as nameUser','users.apellido as apellidoUser','clientes.nombreCliente','clientes.apellidoCliente')
                ->leftJoin('users','facturas.idUsuario','=','users.id')
                ->leftJoin('tipofacturas','facturas.idTipoFactura','=','tipofacturas.idTipoFactura')
                ->leftJoin('clientes','facturas.idCliente','=','clientes.id')
                ->orderBy('facturas.id', 'desc')
                ->where('facturas.idEmpresa', Auth::user()->idEmpresa);

            if ($buscar) {
                // Aplicar el filtro de búsqueda
                $query->where(function ($q) use ($buscar) {
                    $q->where('facturas.numeroFactura', 'like', '%' . $buscar . '%')
                    ->orWhere('facturas.fechaModificacion', 'like', '%' . $buscar . '%');
                });
            }
        
            $listadofacturas = $query->get();

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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Obtengo Pto Venta ||Proxima iteracion
        //$ptoVenta=DB::table('puntoVenta')->where('id',1)->first();
        $ptoVenta = 1;

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
        // llamo al metodo validarForm
        // $this->validarForm($request);
        
        try {
            // Valido El stock de cada articulo
            foreach ($request->detalles as $detalle) {
                $stock = Stock::where('idArticulo', $detalle['idArticulo'])->first();
                if ($stock->cantidad < $detalle['cantidadArticulo']) {
                   // Enviar Throw Exception
                    throw new \Exception("No hay Stock Suficiente para el Articulo: " . $detalle['nombre']);
                }
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
            // Instancio DetalleFactura
            $detalleFactura = new DetalleFactura;
            //$detalleFactura::create();
            // modificar la manera que inserto los registros y hacerlo uno por uno craendo un nuevo objeto
            foreach ($detalleReq as $detalleF) {
                $detalleFactura->create($detalleF);
            }
            // No me permitia auditar con el metodo insert porque es de tipo query builder y no de tipo eloquent
            //$detalleFactura->insert($detalleReq);
            DB::commit();
            // Hago un refresh de la instancia para que me traiga los detalles
            $detalleFactura->refresh();
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
        $factura= Factura::with('puntoventa','tipofactura')->selectRaw("id,descuento,totalFactura,fechaModificacion,totalFactura,idPuntoVenta,idTipoFactura, CONCAT(LPAD(numeroFactura, 6, '0')) as numeroFactura")
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

    public function reporteventas(){

        $fecha_actual = Carbon::now();
        $inicio_mes_actual = Carbon::now()->startOfMonth();

        $total_facturada_mes_actual = Factura::where('idEmpresa',Auth::user()->idEmpresa)
                                                ->whereBetween('fechaModificacion', [$inicio_mes_actual->toDateString(), $fecha_actual->toDateString()])
                                                ->sum('totalFactura');
        
        $cantidad_facturas=Factura::where('idEmpresa',Auth::user()->idEmpresa)
            ->whereBetween('fechaModificacion', [$inicio_mes_actual->toDateString(), $fecha_actual->toDateString()])
            ->count();
        
        $cantidad_articulos_mes_actual = Factura::where('idEmpresa',Auth::user()->idEmpresa)
            ->whereBetween('fechaModificacion', [$inicio_mes_actual->toDateString(), $fecha_actual->toDateString()])
            ->join('detallesfacturas','facturas.id','=','detallesfacturas.idFactura')
            ->sum('detallesfacturas.cantidadArticulo');

        $ArtMasVendido = 'Sin Articulos';

        // Metricas Para Grafico de Barras ChartJS
        $numeroMes      = $fecha_actual->month;
        $nombreMes      = $fecha_actual->formatLocalized('%B');
        $nameMeses      = [];
        $amount         = [];
        $countFacturas  = [];
        $countArticulos = [];

        for ($i=1; $i <= $numeroMes ; $i++) { 
            // Get Current Month with dates
            $currentMonth = $fecha_actual->month($i);
            $nameofMonth = $currentMonth->formatLocalized('%B');
            array_push($nameMeses,$nameofMonth);
            $startOfMonth = $currentMonth->startOfMonth()->toDateString();
            $endOfMonth = date("Y-m-t", strtotime($currentMonth->toDateString()));

            $total_facturada_mes_actual = Factura::where('idEmpresa',Auth::user()->idEmpresa)
                    ->whereBetween('fechaModificacion', [$startOfMonth, $endOfMonth])
                    ->sum('totalFactura');
            array_push($amount,$total_facturada_mes_actual);

            $cantidad_facturas=Factura::where('idEmpresa',Auth::user()->idEmpresa)
                ->whereBetween('fechaModificacion', [$startOfMonth, $endOfMonth])
                ->count();
            array_push($countFacturas,$cantidad_facturas);

            $cantidad_articulos_mes_actual = Factura::where('idEmpresa',Auth::user()->idEmpresa)
                ->whereBetween('fechaModificacion', [$startOfMonth, $endOfMonth])
                ->join('detallesfacturas','facturas.id','=','detallesfacturas.idFactura')
                ->sum('detallesfacturas.cantidadArticulo');
            array_push($countArticulos,$cantidad_articulos_mes_actual);

        }
        // Metricas Grafico Pie
        $cantidadArticulos = DetalleFactura::select('a.nombreArticulo',DB::raw('SUM(detallesfacturas.cantidadArticulo) as total'))
            ->join('facturas as f','detallesfacturas.idFactura','=','f.id')
            ->join('articulos as a','detallesfacturas.idArticulo','=','a.id')
            ->where('f.idEmpresa',Auth::user()->idEmpresa)
            ->groupBy('detallesfacturas.idArticulo', 'a.id', 'a.nombreArticulo')
            ->orderByDesc('total')
            ->pluck('total','nombreArticulo');
    //DetalleFactura::whereDate();
        return response()->json([
            "cantVentaMensual"          =>$cantidad_facturas,
            "totalVentaMensual"         =>$total_facturada_mes_actual,
            "cantidadArticulosMensual"  =>$cantidad_articulos_mes_actual,
            "ArtMasVendido"             =>$ArtMasVendido,
            "toCurrentMonths"           =>$nameMeses,
            "amount"                    =>$amount,
            "countFacturas"             =>$countFacturas,
            "countArticulos"            =>$countArticulos,
            'cantidadArticulos'         =>$cantidadArticulos,
        ],200);
         

    }

}
