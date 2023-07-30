<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Stock;


class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
public function run()
    {
        // Datos para insertar en la tabla "stock"
        $arrStock = [
            [
                'idArticulo' => 1,
                'cantidad' => 100,
                'cantidadMinima' => 5,
                'idEmpresa' => 1
            ],
            [
                'idArticulo' => 2,
                'cantidad' => 60,
                'cantidadMinima' => 5,
                'idEmpresa' => 1
            ],
            [
                'idArticulo' => 3,
                'cantidad' => 150,
                'cantidadMinima' => 6,
                'idEmpresa' => 1
            ],
            [
                'idArticulo' => 4,
                'cantidad' => 50,
                'cantidadMinima' => 4,
                'idEmpresa' => 1
            ],
        ];

        // Insertar los datos en la tabla "stock"
        $stock= new Stock;
        $stock->insert($arrStock); 

    }
}
