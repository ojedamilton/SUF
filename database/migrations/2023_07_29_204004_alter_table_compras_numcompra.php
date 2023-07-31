<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableComprasNumcompra extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()

    {
        Schema::table('compras', function (Blueprint $table) {
            // Verificar si la columna ya existe antes de agregarla
            if (!Schema::hasColumn('compras', 'numeroCompra')) {
                $table->string('numeroCompra', 6)->nullable()->after('id');
            }
        });

        // Agregar el zerofill para que grabe los ceros a la izquierda (000001)
        DB::statement('ALTER TABLE compras MODIFY numeroCompra INT(6) ZEROFILL NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('compras', function (Blueprint $table) {
            //
        });
    }
}
