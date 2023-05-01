<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\DetalleFactura;
use Dompdf\Dompdf;
use Carbon\Carbon;

class FacturaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    
    private function validarForm( Request $request )
    {
        
        $request->validate(
            [
              'precio.required'=>'El campo "Precio" es obligatorio.',
              'precio.numeric'=>'Complete el campo Precio con un número.',
              'id_cliente.integer'=>'Seleccione un Cliente',
              'fecha.required'=>'La fecha es requerida',
            ]
        );
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Obtengo Pto Venta ||Proxima iteracion
        //$ptoVenta=DB::table('puntoVenta')->where('id',1)->first();
        $ptoVenta=1;
        $factura = Factura::where('idTipoFactura',$request->factura['tipoFacturaId'])->first();
        // ultimo Numero por cada Tipo de Factura 
        if($factura){
            $ultimoNum=Factura::selectRaw("id,CONCAT(LPAD(numeroFactura+1, 6, '0')) as numeroFactura")
                            ->where("idTipoFactura",$request->factura['tipoFacturaId'])
                            ->orderBy("numeroFactura","desc")
                            ->pluck('numeroFactura')
                            ->first();
        }else{
            $ultimoNum='000001';
        }
        
        // Validar los datos
        // si falla devuelve variable $errors  
        $this->validarForm($request);
        
        try {
            //dd($request);
            // Comienzo Transaccion
            DB::beginTransaction();
            // Instancio Factura
            $factura = new Factura;
            $factura->numeroFactura=$ultimoNum;
            $factura->fechaModificacion=$request->factura['fecha'];
            $factura->estadoFactura=1;
            $factura->idCliente=$request->factura['id_cliente'];
            $factura->idValor=$request->factura['pago'];
            $factura->idUsuario=Auth::user()->id;
            $factura->idTipoFactura=$request->factura['tipoFacturaId'];
            $factura->idEmpresa=Auth::user()->idEmpresa;
            $factura->idpuntoVenta=$ptoVenta;
            $factura->totalFactura=$request->factura['totalFactura'];
            $factura->descuento=$request->factura['descuento'];
            $factura->save();
            
            // Instancio Detalles
            $detalleReq=$request->detalles;
            // paso por referencia (&) asi modifico el array original
            foreach ($detalleReq as &$detalle) {
                $detalle['idFactura']=$factura->id; 
                unset($detalle['nombre']);
            }
            $detalleFactura = new DetalleFactura;
            // con metodo Create paso Array
            //dd($detalleReq); 
            $detalleFactura->insert($detalleReq);
            //dd($detalleReq); 
           DB::commit();
        } catch (\Throwable $th) {
           DB::rollback();
            //throw $th;
            return 'Error en la Query'.$th;
        }

        return 'Todo Ok';
  

        // Instanciar la factura , modelo Factura
        /* 
        DB::transaccion
         fecha 
         idusuario
         asdadsa
         asdsasd
         
         salio OK
         id_fact
         id_detalle
         total 
         cantidad
         detalletotal falla 

        finalizas
        
        */

        // si va todo bien obtenemos ese id_factura
        // Instanciamos el detalle de factura llamando al modelo DetalleFactura

    }
    public function getAllFacturas(Request $request){
       
            //dd('llegue aca');
            // Si quieren Ingresar sin un request , redirecciona al home 
            if(!$request->ajax())return redirect('/');

            $listadofacturas=Factura::
                             orderBy('id','desc')
                             ->where('idEmpresa',Auth::user()->idEmpresa)
                             ->get();
  
           return[
               'listadofacturas'=>$listadofacturas,
           ]; 
       
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
        $cantidad_facturas=Factura::where('idEmpresa',Auth::user()->idEmpresa??1)->count();                                          
        

        return [
          "cantVentaSemanal"=>$cantidad_facturas,
          "totalVentaSemanal"=>$total_facturada_semana_actual
        ];
         

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function show(Factura $factura)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function edit(Factura $factura)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Factura $factura)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function destroy(Factura $factura)
    {
        //
    }
}
