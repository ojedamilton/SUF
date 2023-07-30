<?php

namespace App\Repositories;

use App\Models\Categoria;

class CategoriaRepository {

    private $model;

    public function __construct(){

        $this->model = new Categoria();
    }
    
  
    public function all($buscar,$idEmpresa){

        return $this->model->where('idEmpresa',$idEmpresa)
                            ->where('estadoCategoria',1)
                            ->where('nombreCategoria','like','%'.$buscar.'%')
                            ->orderBy('nombreCategoria','asc')
                            ->paginate(10);
    }
}