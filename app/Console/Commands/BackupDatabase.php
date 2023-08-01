<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear Backup de la base de datos';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {

            $host     = env('DB_HOST');
            $database = env('DB_DATABASE');
            $username = env('DB_USERNAME');
            $password = env('DB_PASSWORD');
            // date para que el backup tenga la fecha , horas , minutos y segundos actuales
            $dumpPath = storage_path('app/backup/' . date('Y-m-d_His') . '_backup.sql');
    
            // Ejecuta el comando mysqldump para hacer el backup sin pedirme la confirmacion de la contraseña
            exec("mysqldump -h $host -u $username -p$password $database > $dumpPath");
            
            $this->info("El backup de la DB se hizo Correctamente. Ubicación : $dumpPath");
        } catch (\Throwable $th) {
            Log::info($th->getMessage());
            $this->info("Error al hacer el backup de la DB");
        }
       
    }
}
