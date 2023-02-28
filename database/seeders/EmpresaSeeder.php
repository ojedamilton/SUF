<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arrayEmpresas=[
            [
             'id'=>1,'nombreEmpresa'=>'Empresuf','cuitEmpresa'=>23344439872,
             'direccionEmpresa'=>'Calle Falsa 123','idTipoEmpresa'=>1,'estadoEmpresa'=>1,
             'razonSocial'=>'Empresuf Srl','ingresosBrutosEmpresa'=>1,'telEmpresa'=>11223344,
             'inicioActividades'=>'1900-01-01'
            ]
        ];

        DB::table('empresas')->insert($arrayEmpresas);
    }
}
