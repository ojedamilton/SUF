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
        Schema::table('empresas', function (Blueprint $table) {
            $table->string('razonSocial',100)->nullable();
            $table->integer('ingresosBrutosEmpresa');
            $table->integer('telEmpresa');
            $table->date('inicioActividades')->nullable();
            $table->renameColumn('nombreFantasia', 'nombreEmpresa');
            $table->biginteger('cuitEmpresa')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn('razonSocial');
            $table->dropColumn('ingresosBrutosEmpresa');
            $table->dropColumn('telEmpresa');
            $table->dropColumn('inicioActividades');
            $table->renameColumn('nombreEmpresa', 'nombreFantasia');
            $table->integer('cuitEmpresa')->change();
        });
    }
};
