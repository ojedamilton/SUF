<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('nombreFantasia');
            $table->integer('cuitEmpresa');
            $table->string('direccionEmpresa');
            $table->unsignedBigInteger('idTipoEmpresa');
            $table->boolean('estadoEmpresa');

            $table->foreign('idTipoEmpresa')->references('id')->on('tiposEmpresas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empresas');
    }
}
