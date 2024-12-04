<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotasCreditoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notascredito', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('numeroNotaCredito')->nullable()->zerofill();
            $table->date('fechaNotaCredito');
            $table->tinyInteger('estadoNotaCredito')->default(1);
            $table->unsignedBigInteger('idCliente');
            $table->unsignedBigInteger('idValor');
            $table->unsignedBigInteger('idUsuario');
            $table->unsignedBigInteger('idEmpresa')->nullable();
            $table->decimal('totalNotaCredito', 8, 2);
            $table->unsignedBigInteger('idpuntoVenta')->nullable();
            $table->unsignedBigInteger('idTipoFactura')->nullable();
            $table->integer('descuento')->nullable();
            $table->timestamps();

            $table->foreign('idCliente')->references('id')->on('clientes')->onDelete('cascade');
            $table->foreign('idValor')->references('id')->on('valores')->onDelete('cascade');
            $table->foreign('idUsuario')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('idEmpresa')->references('id')->on('empresas')->onDelete('set null');
            $table->foreign('idpuntoVenta')->references('id')->on('puntoventa')->onDelete('set null');
            $table->foreign('idTipoFactura')->references('idTipoFactura')->on('tipofacturas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notascredito');
    }
}
