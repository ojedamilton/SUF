<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Valor;

class ValorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arrValores =[
            ['nombreValor' => 'Efectivo', 'estadoValor'=>1, 'idEmpresa' => 1],
            ['nombreValor' => 'Credito', 'estadoValor'=>1, 'idEmpresa' => 1],
            ['nombreValor' => 'Cheque', 'estadoValor'=>1, 'idEmpresa' => 1],
            ['nombreValor' => 'Transferencia', 'estadoValor'=>1, 'idEmpresa' => 1]
        ] ;
       $valor= new Valor;
       $valor->insert($arrValores);
    }
}
