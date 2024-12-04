<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\NotaCredito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\DetalleNotaCredito;
use App\Models\Stock;
use App\Repositories\NotaCreditoRepository;
use Dompdf\Dompdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class NotaCreditoController extends Controller
{
    protected $notaCreditoRepository;

    public function __construct(NotaCreditoRepository $notaCreditoRepository)
    {
        $this->notaCreditoRepository = $notaCreditoRepository;    
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllNotaCredito(Request $request)
    {

        try {
            $buscar= $request->buscar;
            
            $listadonotacredito = $this->notaCreditoRepository->all($buscar);

            return response()->json([
                'success' => true,
                'message' => 'Listado de Notas de Credito',
                'listadonotacredito' => $listadonotacredito,
            ], 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al Listar Notas de Crédito',
                'listadonotacredito' => null,
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
                'notascredito.totalNotaCredito.required' => 'El campo "Precio" es obligatorio.',
                'notascredito.id_cliente.integer' => 'Seleccione un Cliente',
                'notascredito.fecha.required' => 'La fecha es requerida',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error En la Validacion al Crear Nota de Crédito',
                'notacredito' => null,
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
        $ptoVenta = 1;

        // Obtener la empresa del usuario logueado
        $idEmpresa = Auth::user()->idEmpresa;

        $notacredito = NotaCredito::where('idTipoFactura', $request->notacredito['tipoFacturaId'])
                            ->where('idEmpresa', $idEmpresa) // Agregar filtro por empresa
                            ->first();
        // ultimo Numero por cada Tipo de Nota de Credito 
        if ($notacredito) {
            $ultimoNum = NotaCredito::selectRaw("id,CONCAT(LPAD(numeroNotaCredito+1, 6, '0')) as numeroNotaCredito")
                ->where("idTipoFactura", $request->notacredito['tipoFacturaId'])
                ->where("idEmpresa", $idEmpresa) // Agregar filtro por empresa
                ->orderBy("numeroNotaCredito", "desc")
                ->pluck('numeroNotaCredito')
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
            // Instancio Nota de Credito
            $notacredito = new NotaCredito;
            $notacredito->numeroNotaCredito = $ultimoNum;
            $notacredito->fechaNotaCredito = $request->notacredito['fechaNotaCredito'];
            $notacredito->estadoNotaCredito = 1;
            $notacredito->idCliente = $request->notacredito['id_cliente'];
            $notacredito->idValor = $request->notacredito['pago'];
            $notacredito->idUsuario = Auth::user()->id;
            $notacredito->idTipoFactura = $request->notacredito['tipoFacturaId'];
            $notacredito->idEmpresa = Auth::user()->idEmpresa;
            $notacredito->idpuntoVenta = $ptoVenta;
            $notacredito->totalNotaCredito = $request->notacredito['totalNotaCredito'];
            $notacredito->descuento = $request->notacredito['descuento'];
            $notacredito->save();

            // Instancio Detalles
            $detalleReq = $request->detalles;
            // Agrego Id Nota de Credito a cada detalle y elimino el nombre
            foreach ($detalleReq as $key=>$detalle) {
                $detalleReq[$key]['idNotaCredito'] = $notacredito->id;
                unset($detalleReq[$key]['nombre']);
            }

            // Creo los detalles
            $notacredito->detallesnotacredito()->createMany($detalleReq);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Nota de Crédito Creada Correctamente',
                'notacredito' => $notacredito->id,
                'detalle' => $detalleReq,
            ], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al Crear Nota de Crédito',
                'notacredito' => null,
                'detalle' => null,
            ], 500);
        }
    }

   
    public function getNotaCreditoById(Request $request){
    
        // Si quieren Ingresar sin un request , redirecciona al home 
        if(!$request->ajax())return redirect('/');

        $notacredito=NotaCredito::find($request->id);
        $notacredito= NotaCredito::with('puntoventa','tipofactura')->selectRaw("id,descuento,totalNotaCredito,fechaNotaCredito,totalNotaCredito,idPuntoVenta,idTipoFactura, CONCAT(LPAD(numeroNotaCredito, 6, '0')) as numeroNotaCredito")
                                ->where('id',$request->id)
                                ->first();

       return[
           'notacredito'=>$notacredito,
       ]; 
   
} 
    public function  getDetallesNotaCreditoById(Request $request){
       
        // Si quieren Ingresar sin un request , redirecciona al home 
        if(!$request->ajax())return redirect('/');

        
        $idNotaCredito=$request->id;
        $detallesById=DetalleNotaCredito::with('articulo')
            ->where('idNotaCredito',$idNotaCredito)
            ->get();

       return[
           'detallesbyid'=>$detallesById,
       ]; 
   
    }


    public function descargarNotaCredito(Request $request)
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

        $total_notacredito_mes_actual = NotaCredito::where('idEmpresa',Auth::user()->idEmpresa)
                                                ->whereBetween('fechaNotaCredito', [$inicio_mes_actual->toDateString(), $fecha_actual->toDateString()])
                                                ->sum('totalNotaCredito');
        
        $cantidad_notacredito=NotaCredito::where('idEmpresa',Auth::user()->idEmpresa)
            ->whereBetween('fechaNotaCredito', [$inicio_mes_actual->toDateString(), $fecha_actual->toDateString()])
            ->count();
        
        $cantidad_articulos_mes_actual = NotaCredito::where('idEmpresa',Auth::user()->idEmpresa)
            ->whereBetween('fechaNotaCredito', [$inicio_mes_actual->toDateString(), $fecha_actual->toDateString()])
            ->join('detallesnotascredito','notascredito.id','=','detallesnotascredito.idNotaCredito')
            ->sum('detallesnotascredito.cantidadArticulo');

        $ArtMasVendido = 'Sin Articulos';

        // Metricas Para Grafico de Barras ChartJS
        $numeroMes      = $fecha_actual->month;
        $nombreMes      = $fecha_actual->formatLocalized('%B');
        $nameMeses      = [];
        $amount         = [];
        $countNotaCredito  = [];
        $countArticulos = [];

        for ($i=1; $i <= $numeroMes ; $i++) { 
            // Get Current Month with dates
            $currentMonth = $fecha_actual->month($i);
            $nameofMonth = $currentMonth->formatLocalized('%B');
            array_push($nameMeses,$nameofMonth);
            $startOfMonth = $currentMonth->startOfMonth()->toDateString();
            $endOfMonth = date("Y-m-t", strtotime($currentMonth->toDateString()));

            $total_notacredito_mes_actual = NotaCredito::where('idEmpresa',Auth::user()->idEmpresa)
                    ->whereBetween('fechaNotaCredito', [$startOfMonth, $endOfMonth])
                    ->sum('totalNotaCredito');
            array_push($amount,$total_notacredito_mes_actual);

            $cantidad_notacredito=NotaCredito::where('idEmpresa',Auth::user()->idEmpresa)
                ->whereBetween('fechaNotaCredito', [$startOfMonth, $endOfMonth])
                ->count();
            array_push($countNotaCredito,$cantidad_notacredito);

            $cantidad_articulos_mes_actual = NotaCredito::where('idEmpresa',Auth::user()->idEmpresa)
                ->whereBetween('fechaNotaCredito', [$startOfMonth, $endOfMonth])
                ->join('detallesnotascredito','notacredito.id','=','detallesnotascredito.idNotaCredito')
                ->sum('detallesnotascredito.cantidadArticulo');
            array_push($countArticulos,$cantidad_articulos_mes_actual);

        }
        // Metricas Grafico Pie
        $cantidadArticulos = DetalleNotaCredito::select('a.nombreArticulo',DB::raw('SUM(detallesnotascredito.cantidadArticulo) as total'))
            ->join('notascredito as f','detallesnotascredito.idNotaCredito','=','f.id')
            ->join('articulos as a','detallesnotascredito.idArticulo','=','a.id')
            ->where('f.idEmpresa',Auth::user()->idEmpresa)
            ->groupBy('detallesnotascredito.idArticulo', 'a.id', 'a.nombreArticulo')
            ->orderByDesc('total')
            ->pluck('total','nombreArticulo');
    //DetalleNotaCredito::whereDate();
        return response()->json([
            "cantVentaMensual"          =>$cantidad_notacredito,
            "totalVentaMensual"         =>$total_notacredito_mes_actual,
            "cantidadArticulosMensual"  =>$cantidad_articulos_mes_actual,
            "ArtMasVendido"             =>$ArtMasVendido,
            "toCurrentMonths"           =>$nameMeses,
            "amount"                    =>$amount,
            "countNotaCredito"          =>$countNotaCredito,
            "countArticulos"            =>$countArticulos,
            'cantidadArticulos'         =>$cantidadArticulos,
        ],200);
         

    }

}
