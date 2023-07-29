<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallescomprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detallescompras', function (Blueprint $table) {
            $table->id();
            $table->decimal('precioCompra', 8, 2);
            $table->integer('cantidadArticulo');
            $table->unsignedBigInteger('idCompra');
            $table->unsignedBigInteger('idArticulo');
            $table->decimal('totalDetalle', 8, 2);
            $table->timestamps();
    
            // Definir las claves foráneas
            $table->foreign('idCompra')->references('id')->on('compras');
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
        Schema::dropIfExists('detallescompras');
    }
}
