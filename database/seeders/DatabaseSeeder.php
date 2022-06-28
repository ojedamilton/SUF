<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // llamo a los Seeder's 
        $this->call(AccionSeeder::class);
        $this->call(GrupoSeeder::class);
        $this->call(GrupoAccionSeeder::class);
        $this->call(ClienteSeeder::class);
        $this->call(ValorSeeder::class);
        $this->call(UserSeeder::class); 
       
      
    }
}
