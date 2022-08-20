<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Articulo;

class ArticuloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // creaamos vector con arreglos que luego van a ser insertados en la bd
        // 'Pantalon' 1   
        // 'Camisas', 2 
        // 'Remeras', 3
        // 'Calzados 4

        $arrArticulos =[
            ['nombreArticulo' => 'Jean Kosiuko', 'precio'=> 12500  ,'stock'=> 15 ,'estadoArticulo'=>1 ,'idCategoria'=>1],
            ['nombreArticulo' => 'Camisa Cuadros', 'precio'=> 6200 ,'stock'=> 11 ,'estadoArticulo'=>1 ,'idCategoria'=>2],
            ['nombreArticulo' => 'Remera Simpson', 'precio'=> 5500 ,'stock'=> 20 ,'estadoArticulo'=>1 ,'idCategoria'=>3],
            ['nombreArticulo' => 'Zapatilla Deportiva', 'precio'=> 20000 ,'stock'=> 18 ,'estadoArticulo'=>1 ,'idCategoria'=>4],
        ] ;

       $articulo= new Articulo;
       $articulo->insert($arrArticulos); 

    }
}
