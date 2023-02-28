<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoFacturaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arrayTipoFacturas=[
            ['idTipoFactura'=>1,'tipoFactura'=>'A','estadoTipoFactura'=>1],
            ['idTipoFactura'=>2,'tipoFactura'=>'B','estadoTipoFactura'=>1],
            ['idTipoFactura'=>3,'tipoFactura'=>'C','estadoTipoFactura'=>1]
        ];
         
        DB::table('tipoFacturas')->insert($arrayTipoFacturas);
    }
}
