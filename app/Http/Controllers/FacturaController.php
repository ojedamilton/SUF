<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\DetalleFactura;

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
        // $request->factura  {fecha,valor,id_cliente}
        // $request->detalles {}
        // Validar los datos
        // si falla devuelve variable $errors  
        $this->validarForm($request);
        
        try {
            //dd($request);
            // Comienzo Transaccion
            DB::beginTransaction();
            // Instancio Factura
            $factura = new Factura;
            $factura->fechaModificacion=$request->factura['fecha'];
            $factura->estadoFactura=1;
            $factura->idCliente=$request->factura['id_cliente'];
            $factura->idValor=$request->factura['pago'];
            $factura->idUsuario=Auth::user()->id;
            $factura->totalFactura=$request->factura['totalFactura'];
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
            $detalleFactura->create($detalleReq);
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
