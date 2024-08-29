<?php

namespace App\Repositories;

use App\Models\Compra;
use Illuminate\Support\Facades\Auth;

class CompraRepository {

    private $model;

    public function __construct(){

        $this->model = new Compra();
    }
    
  
    public function all($buscar=null){

        $query = $this->model->query()
            //->with('detallescompra', 'detallescompra.articulo','detallescompra.articulo.stock')
            ->select('compras.id','compras.numeroCompra','compras.totalCompra','compras.fechaCompra') //'users.name as nameUser','users.apellido as apellidoUser','proveedors.nombreProveedor','proveedors.apellidoProveedor')
            //->leftJoin('users','compras.idUsuario','=','users.id')
            //->leftJoin('proveedors','compras.idProveedor','=','proveedors.id')
            ->orderBy('compras.id', 'desc')
            ->where('compras.idEmpresa', Auth::user()->idEmpresa);
    
        if ($buscar) {
            // Aplicar el filtro de búsqueda
            $query->where(function ($q) use ($buscar) {
                $q->where('compras.numeroCompra','like', '%' . $buscar . '%')
                    ->orWhere('compras.fechaCompra','like', '%' . $buscar . '%');
            });
        }
        
        return $query->get();
    }
}