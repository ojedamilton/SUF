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
        Schema::table('clientes', function (Blueprint $table) {
            $table->unsignedBigInteger('idEmpresa')->nullable()->after('estadoCliente');
            $table->unsignedBigInteger('idSituacion')->nullable()->after('idEmpresa');
            $table->string('razonSocial',100)->nullable()->after('idSituacion');

            // Agregar llave foránea a la tabla "empresas"
            $table->foreign('idEmpresa')->references('id')->on('empresas')
                    ->onDelete('set null')->onUpdate('cascade');

            // Agregar llave foránea a la tabla "situacionFiscal"
            $table->foreign('idSituacion')->references('id')->on('situacionFiscal')
                    ->onDelete('set null')->onUpdate('cascade');
    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropForeign(['idEmpresa']);
            $table->dropForeign(['idSituacion']);
            $table->dropColumn('idEmpresa');
            $table->dropColumn('idSituacion');
            $table->renameColumn('cuitCliente', 'dniCliente');
        });
    }
};
