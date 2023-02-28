<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoEmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arrayTipoEmpresa=[
            ['id'=>1,'nombreTipoEmpresa'=>'Responsable inscripto','estadoTipoEmpresa'=>1],
            ['id'=>2,'nombreTipoEmpresa'=>'Excento Iva','estadoTipoEmpresa'=>1],
            ['id'=>3,'nombreTipoEmpresa'=>'Monotributista','estadoTipoEmpresa'=>1]
        ];
         
        DB::table('tiposEmpresas')->insert($arrayTipoEmpresa);
    }
}
