<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use  App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // corremos los seeders

        $arrCategorias =[
            ['nombreCategoria' => 'Pantalones', 'estadoCategoria'=>1, 'idEmpresa' => 1],
            ['nombreCategoria' => 'Camisas', 'estadoCategoria'=>1 , 'idEmpresa' => 1],
            ['nombreCategoria' => 'Remeras', 'estadoCategoria'=>1 , 'idEmpresa' => 1],
            ['nombreCategoria' => 'Calzados', 'estadoCategoria'=>1, 'idEmpresa' => 1]
        ] ;

       $categoria= new Categoria;
       $categoria->insert($arrCategorias); 

    }
}
