<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveStockFromArticulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articulos', function (Blueprint $table) {
            $table->dropColumn('stock');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('articulos', function (Blueprint $table) {
            // Utilizamos "dropIfExists" para eliminar la columna "stock"
            // solo si existe en la tabla "articulos".
            if (Schema::hasColumn('articulos', 'stock')) {
                $table->dropIfExists('stock');
            }
        });
    }
}
