<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository {

    private $model;

    public function __construct(){

        $this->model = new User();
    }
    
    public function all($idEmpresa){

        return $this->model->where('idEmpresa',$idEmpresa)
                            ->orderBy('id','desc')
                            ->paginate(10);
    }

    public function search($buscar,$idEmpresa){

        return $this->model->where('idEmpresa',$idEmpresa)
                            ->where(function($query) use ($buscar){
                                $query->where('name','like','%'.$buscar.'%')
                                       ->orWhere('apellido','like','%'.$buscar.'%')
                                       ->orWhere('email','like','%'.$buscar.'%');
                                })    
                            ->orderBy('name','asc')
                            ->paginate(10);
    }
}