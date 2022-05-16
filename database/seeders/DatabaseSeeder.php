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
        // \App\Models\User::factory(10)->create();

        // llamo a los Seeder's 
        $this->call(PermisoSeeder::class);
        $this->call(GrupoSeeder::class);
        $this->call(UserSeeder::class); 
        $this->call(GrupoPermisoSeeder::class);
       
      
    }
}
