<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PermisoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permisos')->insert([
            'nombrePermiso' => 'view',
            'descripcionPermiso' => 'Permiso a todo el contenido',
            'estadoPermiso' => 1,
        ]); 
    }
}
