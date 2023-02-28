<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('facturas', function (Blueprint $table) {
           $table->unsignedBigInteger('idEmpresa')->nullable()->after('idUsuario');
           $table->unsignedBigInteger('idTipoFactura')->nullable()->after('idEmpresa');
           $table->unsignedBigInteger('idpuntoVenta')->nullable()->after('idTipoFactura');


           $table->foreign('idEmpresa')->references('id')->on('empresas')->onDelete('set null')->onUpdate('cascade');;
           $table->foreign('idTipoFactura')->references('idTipoFactura')->on('tipoFacturas')->onDelete('set null')->onUpdate('cascade');;
           $table->foreign('idpuntoVenta')->references('id')->on('puntoVenta')->onDelete('set null')->onUpdate('cascade');;
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->dropForeign(['idEmpresa']);
            $table->dropForeign(['idTipoFactura']);
            $table->dropForeign(['idpuntoVenta']);
            $table->dropColumn('idEmpresa');
            $table->dropColumn('idTipoFactura');
            $table->dropColumn('idpuntoVenta'); 
        });
    }
};
