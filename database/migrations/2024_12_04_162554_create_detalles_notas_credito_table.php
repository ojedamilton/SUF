<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallesNotasCreditoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detallesnotascredito', function (Blueprint $table) {
            $table->id();
            $table->decimal('precioVenta', 8, 2);
            $table->integer('cantidadArticulo');
            $table->unsignedBigInteger('idNotaCredito');
            $table->unsignedBigInteger('idArticulo');
            $table->decimal('totalDetalle', 8, 2);
            $table->timestamps();

            $table->foreign('idNotaCredito')->references('id')->on('notascredito')->onDelete('cascade');
            $table->foreign('idArticulo')->references('id')->on('articulos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detallesnotascredito');
    }
}
