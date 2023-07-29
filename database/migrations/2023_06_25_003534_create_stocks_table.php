<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idArticulo');
            $table->integer('cantidad');
            $table->integer('cantidadMinima');
            $table->timestamps();

            // Indicamos cual es la clave foránea de esta tabla:
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
         // Eliminar la llave foránea
         Schema::table('stocks', function (Blueprint $table) {
            $table->dropForeign(['idArticulo']);
        }); 

        Schema::dropIfExists('stocks');
    }
}
