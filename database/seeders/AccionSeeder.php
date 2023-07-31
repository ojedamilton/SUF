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
            ['nombreAccion'=>'viewAdmin','descripcionAccion'=>'Permiso visualizar el contenido','estadoAccion'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['nombreAccion'=>'createAdmin','descripcionAccion'=>'Permiso a crear contenido','estadoAccion'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['nombreAccion'=>'editAdmin','descripcionAccion'=>'Permiso a editar contenido','estadoAccion'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['nombreAccion'=>'deleteAdmin','descripcionAccion'=>'Permiso a eliminar contenido','estadoAccion'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['nombreAccion'=>'reportAdmin','descripcionAccion'=>'Permiso a reportes','estadoAccion'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['nombreAccion'=>'viewComprador','descripcionAccion'=>'Permiso a visualizar contenido compras','estadoAccion'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['nombreAccion'=>'editComprador','descripcionAccion'=>'Permiso a editar contenido compras','estadoAccion'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['nombreAccion'=>'deleteComprador','descripcionAccion'=>'Permiso a eliminar contenido compras','estadoAccion'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['nombreAccion'=>'reportComprador','descripcionAccion'=>'Permiso a reportes compras','estadoAccion'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['nombreAccion'=>'viewVendedor','descripcionAccion'=>'Permiso a visualizar contenido ventas','estadoAccion'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['nombreAccion'=>'editVendedor','descripcionAccion'=>'Permiso a editar contenido ventas','estadoAccion'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['nombreAccion'=>'deleteVendedor','descripcionAccion'=>'Permiso a eliminar contenido ventas','estadoAccion'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['nombreAccion'=>'reportVendedor','descripcionAccion'=>'Permiso a reportes ventas','estadoAccion'=>1,'created_at'=>now(),'updated_at'=>now()],
        ];

        DB::table('acciones')->insert($accionesArray);
    }
}
