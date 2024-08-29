<?php

namespace App\Repositories;

use App\Models\Articulo;

class ArticuloRepository {

    private $model;

    public function __construct(){

        $this->model = new Articulo();
    }
    
  
    public function all($buscar,$idEmpresa){

        return $this->model->with('stock','categoria:id,nombreCategoria','proveedores:id,nombreProveedor')
                            ->where('idEmpresa',$idEmpresa)
                            ->where('estadoArticulo',1)
                            ->where(function($query) use ($buscar){
                                $query->whereLike('nombreArticulo', '%'.$buscar.'%')
                                    ->orWhereLike('id', '%'.$buscar.'%');
                            })
                            ->orderBy('nombreArticulo','asc')
                            ->paginate(10);
    }
}