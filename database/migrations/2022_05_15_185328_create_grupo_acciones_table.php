<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGrupoAccionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grupoAcciones', function (Blueprint $table) {
            $table->unsignedBigInteger('idGrupo');
            $table->unsignedBigInteger('idAccion');
            $table->boolean('estadoGrupoAccion');
            $table->timestamps();

            $table->foreign('idGrupo')->references('id')->on('grupos');
            $table->foreign('idAccion')->references('id')->on('acciones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grupoAcciones');
    }
}
