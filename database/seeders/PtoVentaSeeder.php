<?php

namespace Database\Seeders;

use App\Models\PuntoVenta;
use Illuminate\Database\Seeder;

class PtoVentaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arrPtoVenta =[
            ['numPuntoVenta' => 1350],
            ['numPuntoVenta' => 1351]
            
        ] ;
       $ptoVenta= new PuntoVenta();
       $ptoVenta->insert($arrPtoVenta);
    }
}
