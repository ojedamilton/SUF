<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedAtToDetallescomprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detallescompras', function (Blueprint $table) {
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
        Schema::table('detallescompras', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Elimina la columna deleted_at
        });
    }
}
