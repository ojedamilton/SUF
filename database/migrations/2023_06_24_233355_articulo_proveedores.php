<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ArticuloProveedores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articulo_proveedores', function (Blueprint $table) {
            $table->unsignedBigInteger('idArticulo');
            $table->unsignedBigInteger('idProveedor');
            $table->timestamps();
            // Indicamos cual es la clave foránea de esta tabla:
            $table->foreign('idArticulo')->references('id')->on('articulos');
            $table->foreign('idProveedor')->references('id')->on('proveedors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
          // Eliminar la llave foránea
          Schema::table('articulo_proveedores', function (Blueprint $table) {
            $table->dropForeign(['idArticulo']);
            $table->dropForeign(['idProveedor']);
        }); 
        // Eliminar la tabla
        Schema::dropIfExists('articulo_proveedores');
    }
}
