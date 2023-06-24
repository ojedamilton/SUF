<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProveedorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proveedors', function (Blueprint $table) {
            $table->id();
            $table->string('nombreProveedor');
            $table->string('apellidoProveedor');
            $table->integer('cuitProveedor');
            $table->string('emailProveedor');
            $table->string('direccionProveedor');
            $table->integer('telefonoProveedor');
            $table->boolean('estadoProveedor')->default(1);
            $table->unsignedBigInteger('id_empresa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proveedors');
    }
}
