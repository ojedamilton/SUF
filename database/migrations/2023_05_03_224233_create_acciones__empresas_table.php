<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccionesEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acciones__empresas', function (Blueprint $table) {
            $table->unsignedBigInteger('idAccion');
            $table->unsignedBigInteger('idEmpresa');
            $table->timestamps();
            // Indicamos cual es la clave foránea de esta tabla:
            $table->foreign('idAccion')->references('id')->on('acciones');
            $table->foreign('idEmpresa')->references('id')->on('empresas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {   
        // Eliminar la llave foránea
        Schema::table('acciones__empresas', function (Blueprint $table) {
            $table->dropForeign(['idAccion']);
            $table->dropForeign(['idEmpresa']);
        });
        // Eliminar la llave foránea
        Schema::dropIfExists('acciones__empresas');
    }
}
