<?php

namespace App\Repositories;

use App\Models\Proveedor;

class ProveedorRepository {

    private $model;

    public function __construct(){

        $this->model = new Proveedor();
    }
    
  
    public function all($buscar,$idEmpresa){

        return $this->model->where('id_empresa',$idEmpresa)
                            ->where('estadoProveedor',1)
                            ->where(function($query) use ($buscar){
                                $query->where('nombreProveedor', 'like', '%'.$buscar.'%')
                                    ->orWhere('apellidoProveedor', 'like', '%'.$buscar.'%')
                                    ->orWhere('cuitProveedor', 'like', '%'.$buscar.'%');
                            })
                            ->orderBy('apellidoProveedor', 'asc')
                            ->paginate(10);
    }
}