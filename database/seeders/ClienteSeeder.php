<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arrClientes= [
                        ['nombreCliente'=>'Juan', 'apellidoCliente'=>'Perez','dniCliente'=>25443657,'emailCliente'=>'juanp@gmail.com','direccionCliente'=>'rioja 2333','telefonoCliente'=>4556677,'estadoCliente'=>1],
                        ['nombreCliente'=>'roberto', 'apellidoCliente'=>'Lopez','dniCliente'=>34554668,'emailCliente'=>'robertol@gmail.com','direccionCliente'=>'balcarce 2240','telefonoCliente'=>4556677,'estadoCliente'=>1],
                        ['nombreCliente'=>'adrian', 'apellidoCliente'=>'Drincovich','dniCliente'=>33444313,'emailCliente'=>'adriand@gmail.com','direccionCliente'=>'belgrano 2100','telefonoCliente'=>4556688,'estadoCliente'=>1],
                        ['nombreCliente'=>'pedro', 'apellidoCliente'=>'Suarez','dniCliente'=>26443657,'emailCliente'=>'pedros@gmail.com','direccionCliente'=>'italia 2370','telefonoCliente'=>4556655,'estadoCliente'=>1],
                        ['nombreCliente'=>'anibal', 'apellidoCliente'=>'Pachano','dniCliente'=>32443657,'emailCliente'=>'anibalp@gmail.com','direccionCliente'=>'rivadia 2350','telefonoCliente'=>4556699,'estadoCliente'=>1],
                        ['nombreCliente'=>'javier', 'apellidoCliente'=>'Toledo','dniCliente'=>37443657,'emailCliente'=>'javiert@gmail.com','direccionCliente'=>'dorrego 2200','telefonoCliente'=>4666677,'estadoCliente'=>1],
                        ['nombreCliente'=>'raul', 'apellidoCliente'=>'Gomez','dniCliente'=>42443657,'emailCliente'=>'raulg@gmail.com','direccionCliente'=>'suipacha 6400','telefonoCliente'=>4776677,'estadoCliente'=>1],
                        ['nombreCliente'=>'pablo', 'apellidoCliente'=>'Javkin','dniCliente'=>28443657,'emailCliente'=>'pabloj@gmail.com','direccionCliente'=>'santa fe 6350','telefonoCliente'=>4886677,'estadoCliente'=>1],
                        ['nombreCliente'=>'ariel', 'apellidoCliente'=>'Domanico','dniCliente'=>29443657,'emailCliente'=>'arield@gmail.com','direccionCliente'=>'san juan 5300','telefonoCliente'=>4336677,'estadoCliente'=>1],
                        ['nombreCliente'=>'jacobo', 'apellidoCliente'=>'Winogranch','dniCliente'=>35443657,'emailCliente'=>'jacobow@gmail.com','direccionCliente'=>'zeballos 4300','telefonoCliente'=>4226677,'estadoCliente'=>1]
        ];

        $cliente= new Cliente;
        $cliente->insert($arrClientes);
    }
}
