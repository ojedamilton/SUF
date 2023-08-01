<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GrupoAccionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Se crean Seeder para GrupoAcciones

        // Grupo 1 - Administrador
        // Acciones Asociadas : 2,3,4,5,6.
        $arrayGrupoAcciones = array(
            array('idGrupo' => 1, 'idAccion' => 1, 'estadoGrupoAccion' => 1, 'created_at'=>now(),'updated_at'=>now()),
            array('idGrupo' => 1, 'idAccion' => 2, 'estadoGrupoAccion' => 1, 'created_at'=>now(),'updated_at'=>now()),
            array('idGrupo' => 1, 'idAccion' => 3, 'estadoGrupoAccion' => 1, 'created_at'=>now(),'updated_at'=>now()),
            array('idGrupo' => 1, 'idAccion' => 4, 'estadoGrupoAccion' => 1, 'created_at'=>now(),'updated_at'=>now()),
            array('idGrupo' => 1, 'idAccion' => 5, 'estadoGrupoAccion' => 1, 'created_at'=>now(),'updated_at'=>now()),
            array('idGrupo' => 1, 'idAccion' => 6, 'estadoGrupoAccion' => 1, 'created_at'=>now(),'updated_at'=>now()),
            array('idGrupo' => 1, 'idAccion' => 7, 'estadoGrupoAccion' => 1, 'created_at'=>now(),'updated_at'=>now()),
            array('idGrupo' => 1, 'idAccion' => 8, 'estadoGrupoAccion' => 1, 'created_at'=>now(),'updated_at'=>now()),
            array('idGrupo' => 1, 'idAccion' => 9, 'estadoGrupoAccion' => 1, 'created_at'=>now(),'updated_at'=>now()),
            array('idGrupo' => 1, 'idAccion' => 10, 'estadoGrupoAccion' => 1, 'created_at'=>now(),'updated_at'=>now()),
            array('idGrupo' => 1, 'idAccion' => 11, 'estadoGrupoAccion' => 1, 'created_at'=>now(),'updated_at'=>now()),
            array('idGrupo' => 1, 'idAccion' => 12, 'estadoGrupoAccion' => 1, 'created_at'=>now(),'updated_at'=>now()),
            array('idGrupo' => 1, 'idAccion' => 13, 'estadoGrupoAccion' => 1, 'created_at'=>now(),'updated_at'=>now()),
        );
        DB::table('grupoacciones')->insert($arrayGrupoAcciones);

        // Grupo 2 - Comprador
        // Acciones Asociadas : 7,8,9,10.
        $arrayGrupoAcciones = array(
            array('idGrupo' => 2, 'idAccion' => 6, 'estadoGrupoAccion' => 1, 'created_at'=>now(),'updated_at'=>now()),
            array('idGrupo' => 2, 'idAccion' => 7, 'estadoGrupoAccion' => 1, 'created_at'=>now(),'updated_at'=>now()),
            array('idGrupo' => 2, 'idAccion' => 8, 'estadoGrupoAccion' => 1, 'created_at'=>now(),'updated_at'=>now()),
            array('idGrupo' => 2, 'idAccion' => 9, 'estadoGrupoAccion' => 1, 'created_at'=>now(),'updated_at'=>now()),
        );
        DB::table('grupoacciones')->insert($arrayGrupoAcciones);

        // Grupo 3 - Vendedor
        // Acciones Asociadas : 11,12,13,14.
        $arrayGrupoAcciones = array(
            array('idGrupo' => 3, 'idAccion' => 10, 'estadoGrupoAccion' => 1, 'created_at'=>now(),'updated_at'=>now()),
            array('idGrupo' => 3, 'idAccion' => 11, 'estadoGrupoAccion' => 1, 'created_at'=>now(),'updated_at'=>now()),
            array('idGrupo' => 3, 'idAccion' => 12, 'estadoGrupoAccion' => 1, 'created_at'=>now(),'updated_at'=>now()),
            array('idGrupo' => 3, 'idAccion' => 13, 'estadoGrupoAccion' => 1, 'created_at'=>now(),'updated_at'=>now()),
        );
        DB::table('grupoacciones')->insert($arrayGrupoAcciones);
    }
}
