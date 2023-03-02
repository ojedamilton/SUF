<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableValor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('valores', function (Blueprint $table) {

            $table->unsignedBigInteger('idEmpresa')->nullable()->after('estadoValor');

            // Agregar llave foránea a la tabla "empresas"
            $table->foreign('idEmpresa')->references('id')->on('empresas')
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
        //
    }
}
