<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ValorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $valores=[
            ['nombreValor' => 'efectivo',],
            []
        ];
        DB::table('valores')->insert([
            'nombreValor' => 'efectivo',
            'estado' => 1,

        ]);
    }
}
