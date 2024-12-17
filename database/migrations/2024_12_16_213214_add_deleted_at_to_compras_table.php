<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedAtToComprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('compras', function (Blueprint $table) {
            $table->softDeletes(); // Agrega la columna deleted_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('compras', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Elimina la columna deleted_at
        });
    }
}
