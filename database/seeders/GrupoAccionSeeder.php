<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GrupoAccionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('grupoAcciones')->insert([
            'idGrupo' => 1,
            'idAccion' => 1,
            'estadoGrupoAccion'=>1
        ]);  
    }
}
