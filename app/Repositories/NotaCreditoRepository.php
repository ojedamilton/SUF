<?php

namespace App\Repositories;

use App\Models\NotaCredito;
use Illuminate\Support\Facades\Auth;

class NotaCreditoRepository {

    private $model;

    public function __construct(){

        $this->model = new NotaCredito();
    }
    

    public function all($buscar=null){

        $query = $this->model->query()
            //->with('detallesnotacredito','puntoventa', 'detallesnotacredito.articulo','detallesnotacredito.articulo.stock')
            ->select('notascredito.id','notascredito.idpuntoVenta','notascredito.numeroNotaCredito','notascredito.totalNotaCredito','notascredito.fechaNotaCredito','tipofacturas.tipoFactura','users.name as nameUser','users.apellido as apellidoUser','clientes.nombreCliente','clientes.apellidoCliente')
            ->leftJoin('users','notascredito.idUsuario','=','users.id')
            ->leftJoin('tipofacturas','notascredito.idTipoFactura','=','tipofacturas.idTipoFactura')
            ->leftJoin('clientes','notascredito.idCliente','=','clientes.id')
            ->orderBy('notascredito.id', 'desc')
            ->where('notascredito.idEmpresa', Auth::user()->idEmpresa);

        if ($buscar) {
            // Aplicar el filtro de búsqueda
            $query->where(function ($q) use ($buscar) {
                $q->where('notascredito.numeroNotaCredito', 'like', '%' . $buscar . '%')
                ->orWhere('notascredito.fechaNotaCredito', 'like', '%' . $buscar . '%');
            });
        }
        
        return $query->get();
    }
}