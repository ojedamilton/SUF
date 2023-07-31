<?php

namespace App\Repositories;

use App\Models\Articulo;

class ArticuloRepository {

    private $model;

    public function __construct(){

        $this->model = new Articulo();
    }
    
  
    public function all($buscar,$idEmpresa){

        return $this->model->with('stock')
                            ->where('idEmpresa',$idEmpresa)
                            ->where('estadoArticulo',1)
                            ->where(function($query) use ($buscar){
                                $query->where('nombreArticulo', 'like', '%'.$buscar.'%')
                                    ->orWhere('id', 'like', '%'.$buscar.'%');
                            })
                            ->orderBy('nombreArticulo','asc')
                            ->paginate(10);
    }
}