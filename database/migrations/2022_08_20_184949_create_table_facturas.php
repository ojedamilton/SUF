<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableFacturas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->date('fechaModificacion');
            $table->boolean('estadoFactura');
            $table->timestamps();
            $table->unsignedBigInteger('idCliente');
            $table->unsignedBigInteger('idValor');
            $table->unsignedBigInteger('idUsuario');

            $table->foreign('idCliente')->references('id')->on('clientes');
            $table->foreign('idValor')->references('id')->on('valores');
            $table->foreign('idUsuario')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('facturas');
    }
}
