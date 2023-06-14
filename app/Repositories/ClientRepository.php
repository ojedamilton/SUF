<?php

namespace App\Repositories;

use App\Models\Cliente;

class ClientRepository {

    private $model;

    public function __construct(){

        $this->model = new Cliente();
    }
    
  
    public function all($buscar,$idEmpresa){

        return $this->model->where('idEmpresa',$idEmpresa)
                            ->where('estadoCliente',1)
                            ->where(function($query) use ($buscar){
                                $query->where('nombreCliente', 'like', '%'.$buscar.'%')
                                    ->orWhere('apellidoCliente', 'like', '%'.$buscar.'%')
                                    ->orWhere('dniCliente', 'like', '%'.$buscar.'%');
                            })
                            ->orderBy('nombreCliente', 'asc')
                            ->paginate(10);
    }
}