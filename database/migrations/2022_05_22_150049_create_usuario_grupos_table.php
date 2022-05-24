<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuarioGruposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarioGrupos', function (Blueprint $table) {
            $table->unsignedBigInteger('idUsuario');
            $table->unsignedBigInteger('idGrupo');
            $table->boolean('estadoUsuarioGrupos');
            $table->timestamps();

            $table->foreign('idUsuario')->references('id')->on('users');
            $table->foreign('idGrupo')->references('id')->on('grupos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuarioGrupos');
    }
}
