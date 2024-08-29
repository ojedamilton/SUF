<?php

namespace App\Repositories;

use App\Models\Stock;
use Illuminate\Support\Facades\Auth;

class StockRepository {

    private $model;

    public function __construct(){

        $this->model = new Stock();
    }
    

    public function all($buscar=null){

        if ($buscar == '*') {
            $query = $this->model
                ->with('articulo:id,nombreArticulo')
                ->WhereRelation('articulo', 'idEmpresa', Auth::user()->idEmpresa)
                ->orderBy('id','asc')
                ->paginate(10);
        }else{

            $query = $this->model
                ->with('articulo:id,nombreArticulo')
                ->whereRelation('articulo', 'nombreArticulo', 'like', '%' . $buscar . '%')
                ->WhereRelation('articulo', 'idEmpresa', Auth::user()->idEmpresa)
            ->paginate(5);     
        }
        
        return $query;
    }
}