<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AccionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('acciones')->insert([
            'nombreAccion' => 'view',
            'descripcionAccion' => 'Permiso a todo el contenido',
            'estadoAccion' => 1,
        ]); 
    }
}
