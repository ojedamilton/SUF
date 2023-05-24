<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\usuarioEmpresa;

class UsuarioEmpresas extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arrUsuarioEmpresa =[
            ['idUsuario' => 1, 'idEmpresa'=>1,'estado'=>1]
        ] ;
       $grupo= new usuarioEmpresa();
       $grupo->insert($arrUsuarioEmpresa);
    }
}
