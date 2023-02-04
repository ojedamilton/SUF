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
        $grupos = [
                    [ 'nombreGrupo' => 'administrador','descripcionGrupo' => 'administrador del sistema','estadoGrupo' => 1],
                    [ 'nombreGrupo' => 'comprador','descripcionGrupo' => 'Area de Compras','estadoGrupo' => 1],
                    [ 'nombreGrupo' => 'vendedor','descripcionGrupo' => 'Area de Ventas','estadoGrupo' => 1]
        ];
        // set foreign key 0 for truncate , after that 1
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('grupos')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        DB::table('grupos')->insert($grupos); 
    }
}
