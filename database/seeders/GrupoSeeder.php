<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GrupoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('grupos')->insert([
            'nombreGrupo' => 'administrador',
            'descripcionGrupo' => 'administrador del sistema',
            'estadoGrupo' => 1
        ]); 
    }
}
