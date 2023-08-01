<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Compra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\DetalleCompra;
use App\Models\Stock;
use Dompdf\Dompdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CompraController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllCompras(Request $request)
    {

        try {
            $buscar= $request->buscar;


            $query = Compra::with('detallescompra', 'detallescompra.articulo','detallescompra.articulo.stock')
            ->select('compras.id','compras.numeroCompra','compras.totalCompra','compras.fechaCompra','users.name as nameUser','users.apellido as apellidoUser','proveedors.nombreProveedor','proveedors.apellidoProveedor')
            ->leftJoin('users','compras.idUsuario','=','users.id')
            ->leftJoin('proveedors','compras.idProveedor','=','proveedors.id')
            ->orderBy('compras.id', 'desc')
            ->where('compras.idEmpresa', Auth::user()->idEmpresa);

        if ($buscar) {
            // Aplicar el filtro de búsqueda
            $query->where(function ($q) use ($buscar) {
                $q->where('compras.numeroCompra', 'like', '%' . $buscar . '%')
                ->orWhere('compras.fechaCompra', 'like', '%' . $buscar . '%');
            });
        }
    
        $listadocompras = $query->get();

            return response()->json([
                'success' => true,
                'message' => 'Listado de Compras',
                'listadocompras' => $listadocompras,
            ], 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al Listar Compras',
                'listadocompras' => null,
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
                'compra.totalCompra.required' => 'El campo "Precio" es obligatorio.',
                'compra.id_proveedor.integer' => 'Seleccione un Proveedor',
                'compra.fecha.required' => 'La fecha es requerida',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error En la Validacion al Crear Compra',
                'compra' => null,
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
        // $ptoVenta = 1;

        // Obtener la empresa del usuario logueado
        $idEmpresa = Auth::user()->idEmpresa;

        $compra = Compra::where('idEmpresa', $idEmpresa) // Agregar filtro por empresa
                          ->first();

        // ultimo Numero por cada Tipo de Compra
        if ($compra) {
            $ultimoNum = Compra::selectRaw("id,CONCAT(LPAD(numeroCompra+1, 6, '0')) as numeroCompra")
                // ->where("idTipoCompra", $request->compra['tipoCompraId'])
                ->where("idEmpresa", $idEmpresa) // Agregar filtro por empresa
                ->orderBy("numeroCompra", "desc")
                ->pluck('numeroCompra')
                ->first();
        } else {
            $ultimoNum = '000001';
        }

        // llamo al metodo validarForm
        // $this->validarForm($request);

        try {
            // Comienzo Transaccion
            DB::beginTransaction();
            // Instancio Compra
            $compra = new Compra;
            $compra->numeroCompra = $ultimoNum;
            $compra->fechaCompra = $request->compra['fecha'];
            $compra->estadoCompra = 1;
            $compra->idProveedor = $request->compra['id_proveedor'];
            $compra->idValor = $request->compra['pago'];
            $compra->idUsuario = Auth::user()->id;
            $compra->idEmpresa = Auth::user()->idEmpresa;
            $compra->totalCompra = $request->compra['totalCompra'];
            $compra->descuento = $request->compra['descuento'];
            $compra->save();

            // Instancio Detalles
            $detalleReq = $request->detalles;
            // Agrego Id Compra a cada detalle y elimino el nombre
            foreach ($detalleReq as $key=>$detalle) {
                $detalleReq[$key]['idCompra'] = $compra->id;
                unset($detalleReq[$key]['nombre']);
            }
            // Instancio DetalleCompra
            $detalleCompra = new DetalleCompra;
            //$detalleCompra::create();
            // modificar la manera que inserto los registros y hacerlo uno por uno craendo un nuevo objeto
            foreach ($detalleReq as $detalleF) {
                $detalleCompra->create($detalleF);
            }
            // No me permitia auditar con el metodo insert porque es de tipo query builder y no de tipo eloquent
            //$detalleCompra->insert($detalleReq);
            DB::commit();
            // Hago un refresh de la instancia para que me traiga los detalles
            $detalleCompra->refresh();
            return response()->json([
                'success' => true,
                'message' => 'Compra Creada Correctamente',
                'compra' => $compra->id,
                'detalle' => $detalleReq,
            ], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al Crear Compra',
                'compra' => null,
                'detalle' => null,
            ], 500);
        }
    }
  
    public function getComprasById(Request $request){
      // Si quieren ingresar sin un request, redirecciona al home
      if (!$request->ajax())return redirect('/');

      $compra=Compra::find($request->id);
      $compra = Compra::selectRaw("id, descuento, totalCompra, fechaCompra, idProveedor, idValor, idUsuario, idEmpresa, CONCAT(LPAD(numeroCompra, 6, '0')) as numeroCompra")
          ->where('id', $request->id)
          ->first();

      return [
          'compra' => $compra,
      ];
   }

    public function  getDetallesComprasById(Request $request){

        // Si quieren Ingresar sin un request , redirecciona al home 
        if(!$request->ajax())return redirect('/');


        $idCompra=$request->id;
        $detallescomprasbyid=DetalleCompra::with('articulo')
            ->where('idCompra',$idCompra)
            ->get();

       return[
           'detallescomprasbyid'=>$detallescomprasbyid,
       ]; 
   
    }
    public function descargarCompra(Request $request)
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

    public function reportecompras(){

        $fecha_actual = Carbon::now()->toDateString();
       // dd($fecha_actual);
       /*  $inicio_semana_actual = $fecha_actual->startOfWeek()->startOfDay();
        $fin_semana_actual = $fecha_actual->endOfWeek()->endOfDay(); */
        //whereBetween('fechaModificacion', [$inicio_semana_actual, $fin_semana_actual])
        $total_comprada_semana_actual = Compra::where('idEmpresa',Auth::user()->idEmpresa ?? 1 )
                                                ->where('fechaCompra',$fecha_actual)
                                                ->sum('totalCompra');
        $cantidad_compradas=Compra::where('idEmpresa',Auth::user()->idEmpresa)->count();


        return [
          "cantCompraSemanal"=>$cantidad_compradas,
          "totalCompraSemanal"=>$total_comprada_semana_actual
        ];


    }

}
