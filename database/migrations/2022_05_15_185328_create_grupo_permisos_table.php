<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGrupoPermisosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grupoPermisos', function (Blueprint $table) {
            $table->unsignedBigInteger('idGrupo');
            $table->unsignedBigInteger('idPermiso');
            $table->boolean('estadoGropoPermiso');
            $table->timestamps();

            $table->foreign('idGrupo')->references('id')->on('grupos');
            $table->foreign('idPermiso')->references('id')->on('permisos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grupo_permisos');
    }
}
