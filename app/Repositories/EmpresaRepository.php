<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Empresa;

class EmpresaRepository {

    private $model;

    public function __construct(){

        $this->model = new Empresa();
    }
    

    public function all($buscar){

        if($buscar == 'userListado'){
            $empresas = $this->model->select('id','nombreEmpresa')
                ->where('estadoEmpresa', 1)
                ->paginate(15);
        }else{
        $empresas = $this->model->select('id','nombreEmpresa','razonSocial','cuitEmpresa','ingresosBrutosEmpresa','telEmpresa','direccionEmpresa','inicioActividades','idTipoEmpresa','estadoEmpresa')
            ->where(function ($query) use ($buscar) {
                $query->where('nombreEmpresa', 'like', '%' . $buscar . '%')
                    ->orWhere('cuitEmpresa', 'like', '%' . $buscar . '%');
            })
            ->where('estadoEmpresa', 1)
            ->orderBy('nombreEmpresa', 'asc')
            ->paginate(15);
        }
        return $empresas;
    }

}