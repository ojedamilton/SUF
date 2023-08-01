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
            array('idGrupo' => 1, 'idAccion' => 2),
            array('idGrupo' => 1, 'idAccion' => 3),
            array('idGrupo' => 1, 'idAccion' => 4),
            array('idGrupo' => 1, 'idAccion' => 5),
            array('idGrupo' => 1, 'idAccion' => 6),
        );
        DB::table('grupoacciones')->insert($arrayGrupoAcciones);

        // Grupo 2 - Comprador
        // Acciones Asociadas : 7,8,9,10.
        $arrayGrupoAcciones = array(
            array('idGrupo' => 2, 'idAccion' => 7),
            array('idGrupo' => 2, 'idAccion' => 8),
            array('idGrupo' => 2, 'idAccion' => 9),
            array('idGrupo' => 2, 'idAccion' => 10),
        );
        DB::table('grupoacciones')->insert($arrayGrupoAcciones);

        // Grupo 3 - Vendedor
        // Acciones Asociadas : 11,12,13,14.
        $arrayGrupoAcciones = array(
            array('idGrupo' => 3, 'idAccion' => 11),
            array('idGrupo' => 3, 'idAccion' => 12),
            array('idGrupo' => 3, 'idAccion' => 13),
            array('idGrupo' => 3, 'idAccion' => 14),
        );
        DB::table('grupoacciones')->insert($arrayGrupoAcciones);
    }
}
