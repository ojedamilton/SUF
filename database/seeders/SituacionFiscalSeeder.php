<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SituacionFiscalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arraySituacionFiscal=[
            ['id'=>1,'nombreSituacion'=>'Consumidor Final','estadoSituacion'=>1],
            ['id'=>2,'nombreSituacion'=>'Excento','estadoSituacion'=>1],
            ['id'=>3,'nombreSituacion'=>'Monotributista','estadoSituacion'=>1],
            ['id'=>4,'nombreSituacion'=>'Responsable Inscripto','estadoSituacion'=>1]
        ];

        DB::table('situacionFiscal')->insert($arraySituacionFiscal);
    }
}
