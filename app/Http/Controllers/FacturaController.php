<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\DetalleFactura;
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
            $listadofacturas = Factura::orderBy('id', 'desc')
                ->where('idEmpresa', Auth::user()->idEmpresa)
                ->get();

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

        $fecha_actual = Carbon::now()->toDateString();
       // dd($fecha_actual);
       /*  $inicio_semana_actual = $fecha_actual->startOfWeek()->startOfDay();
        $fin_semana_actual = $fecha_actual->endOfWeek()->endOfDay(); */
        //whereBetween('fechaModificacion', [$inicio_semana_actual, $fin_semana_actual])
        $total_facturada_semana_actual = Factura::where('idEmpresa',Auth::user()->idEmpresa ?? 1 )
                                                ->where('fechaModificacion',$fecha_actual)
                                                ->sum('totalFactura');                                       
        $cantidad_facturas=Factura::where('idEmpresa',Auth::user()->idEmpresa)->count();                                          
        

        return [
          "cantVentaSemanal"=>$cantidad_facturas,
          "totalVentaSemanal"=>$total_facturada_semana_actual
        ];
         

    }

}
