<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GrupoPermisoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('grupoPermisos')->insert([
            'idGrupo' => 1,
            'idPermiso' => 1,
            'estadoGropoPermiso'=>1
        ]);  
    }
}
