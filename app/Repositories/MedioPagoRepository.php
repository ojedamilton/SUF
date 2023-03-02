<?php

namespace App\Repositories;

use App\Models\Valor;

class MedioPagoRepository {

    private $model;

    public function __construct(){

        $this->model = new Valor();
    }
    
  
    public function all($buscar,$idEmpresa){

        return $this->model->where('idEmpresa',$idEmpresa)
                            ->where('nombreValor','like','%'.$buscar.'%')
                            ->orderBy('nombreValor','asc')
                            ->paginate(10);
    }
}