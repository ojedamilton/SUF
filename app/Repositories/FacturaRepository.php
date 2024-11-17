<?php

namespace App\Repositories;

use App\Models\Factura;
use Illuminate\Support\Facades\Auth;

class FacturaRepository {

    private $model;

    public function __construct(){

        $this->model = new Factura();
    }
    

    public function all($buscar=null){

        $query = $this->model->query()
            //->with('detallesfactura','puntoventa', 'detallesfactura.articulo','detallesfactura.articulo.stock')
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
        
        return $query->get();
    }
}