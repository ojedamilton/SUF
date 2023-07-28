<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            $table->integer('numeroCompra')->unsigned();
            $table->date('fechaCompra');
            $table->tinyInteger('estadoCompra');
            $table->unsignedBigInteger('idProveedor');
            $table->unsignedBigInteger('idValor');
            $table->unsignedBigInteger('idUsuario');
            $table->unsignedBigInteger('idEmpresa');
            $table->decimal('totalCompra', 8, 2);
            $table->integer('descuento')->nullable();
            $table->timestamps();
    
            // Definir las claves foráneas
            $table->foreign('idProveedor')->references('id')->on('proveedors');
            $table->foreign('idValor')->references('id')->on('valores');
            $table->foreign('idUsuario')->references('id')->on('users');
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
        Schema::dropIfExists('compras');
    }
}
