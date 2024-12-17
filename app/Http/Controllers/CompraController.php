<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Compra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\DetalleCompra;
use App\Models\Stock;
use App\Repositories\CompraRepository;
use Dompdf\Dompdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class CompraController extends Controller
{
    protected $compraRepository;

    public function __construct(CompraRepository $compraRepository)
    {
        $this->compraRepository = $compraRepository;    
    }

    public function getAllCompras(Request $request): JsonResponse
    {

        try {
    
            $listadocompras = $this->compraRepository->all($request->buscar);

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
    
            // Creo los detalles de la compra
            $compra->detallescompra()->createMany($detalleReq);

            DB::commit();

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

    public function destroy(Request $request)
    {
        // Si quieren ingresar sin un request, redirecciona al home
        if (!$request->ajax()) return redirect('/');
        // Eliminar Compra
        // Try - catch para manejar errores

        try {
            // Comienzo Transaccion
            DB::beginTransaction();
            // Elimino Compra
            $compra = Compra::find($request->idCompra);
            $compra->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Compra Eliminada Correctamente',
            ], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al Eliminar Compra',
            ], 500);
        }
    }

}
