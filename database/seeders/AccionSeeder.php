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
        $accionesArray = [
            ['id' =>1,'nombreAccion'=>'viewAdmin','descripcionAccion'=>'Visualizar el contenido','estadoAccion'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['id' =>2,'nombreAccion'=>'createAdmin','descripcionAccion'=>'Crear contenido','estadoAccion'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['id' =>3,'nombreAccion'=>'editAdmin','descripcionAccion'=>'Editar contenido','estadoAccion'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['id' =>4,'nombreAccion'=>'deleteAdmin','descripcionAccion'=>'Eliminar contenido','estadoAccion'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['id' =>5,'nombreAccion'=>'reportAdmin','descripcionAccion'=>'Informes','estadoAccion'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['id' =>6,'nombreAccion'=>'viewComprador','descripcionAccion'=>'Visualizar contenido compras','estadoAccion'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['id' =>7,'nombreAccion'=>'editComprador','descripcionAccion'=>'Editar contenido compras','estadoAccion'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['id' =>8,'nombreAccion'=>'deleteComprador','descripcionAccion'=>'Eliminar contenido compras','estadoAccion'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['id' =>9,'nombreAccion'=>'reportComprador','descripcionAccion'=>'Reportes compras','estadoAccion'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['id' =>10,'nombreAccion'=>'viewVendedor','descripcionAccion'=>'Visualizar contenido ventas','estadoAccion'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['id' =>11,'nombreAccion'=>'editVendedor','descripcionAccion'=>'Editar contenido ventas','estadoAccion'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['id' =>12,'nombreAccion'=>'deleteVendedor','descripcionAccion'=>'Eliminar contenido ventas','estadoAccion'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['id' =>13,'nombreAccion'=>'reportVendedor','descripcionAccion'=>'Reportes ventas','estadoAccion'=>1,'created_at'=>now(),'updated_at'=>now()],
        ];

        DB::table('acciones')->insert($accionesArray);
    }
}
