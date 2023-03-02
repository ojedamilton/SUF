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
            ['nombreValor' => 'efectivo', 'estadoValor'=>1, 'idEmpresa' => 1],
            ['nombreValor' => 'credito', 'estadoValor'=>1, 'idEmpresa' => 1],
            ['nombreValor' => 'cheque', 'estadoValor'=>1, 'idEmpresa' => 1],
            ['nombreValor' => 'transferencia', 'estadoValor'=>1, 'idEmpresa' => 1]
        ] ;
       $valor= new Valor;
       $valor->insert($arrValores);
    }
}
