<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableDetallesFacturas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detallesFacturas', function (Blueprint $table) {
            $table->id();
            $table->decimal('precioVenta',8,2);
            $table->integer('cantidadArticulo');
            $table->timestamps();
            $table->unsignedBigInteger('idFactura');
            $table->unsignedBigInteger('idArticulo');

            $table->foreign('idFactura')->references('id')->on('facturas');
            $table->foreign('idArticulo')->references('id')->on('articulos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detallesFacturas');
    }
}
