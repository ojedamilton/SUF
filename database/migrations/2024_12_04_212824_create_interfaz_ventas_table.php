<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateInterfazVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interfaz_ventas', function (Blueprint $table) {
            $table->id();
            $table->string('nro_local',5);
            $table->string('nro_contrato');
            $table->string('pos_local',2);
            $table->unsignedBigInteger('id_pto_vta');
            $table->string('ruta');
            $table->unsignedBigInteger('id_empresa');
            $table->timestamps();

            $table->foreign('id_pto_vta')->references('id')->on('puntoventa')->onDelete('cascade');
            $table->foreign('id_empresa')->references('id')->on('empresas')->onDelete('cascade');
        });
            // Agrego el zerofill para que grabe los ceros a la izquierda (000001)
            DB::statement('ALTER TABLE interfaz_ventas MODIFY nro_contrato INT(10) ZEROFILL NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('interfaz_ventas');
    }
}
