<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGruposEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grupos_empresas', function (Blueprint $table) {
            $table->unsignedBigInteger('idGrupo');
            $table->unsignedBigInteger('idEmpresa');
            $table->timestamps();
            // Indicamos cual es la clave foránea de esta tabla:
            $table->foreign('idGrupo')->references('id')->on('grupos');
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
        Schema::table('grupos_empresas', function (Blueprint $table) {
            $table->dropForeign(['idGrupo']);
            $table->dropForeign(['idEmpresa']);
        }); 
        // Eliminar la tabla
        Schema::dropIfExists('grupos_empresas');
    }
}
