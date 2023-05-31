<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\UserGrupo;

class UsuarioGruposSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arrUserGrupo =[
            ['idUsuario' => 1, 'idGrupo'=>1,'estadoUsuarioGrupos'=>1]
        ] ;
       $grupo= new UserGrupo();
       $grupo->insert($arrUserGrupo);
    }
}
