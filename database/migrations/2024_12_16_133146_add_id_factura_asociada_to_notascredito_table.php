<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdFacturaAsociadaToNotascreditoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notascredito', function (Blueprint $table) {
            $table->unsignedBigInteger('idFacturaAsociada');

            $table->foreign('idFacturaAsociada')->references('id')->on('facturas')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notascredito', function (Blueprint $table) {
            $table->dropColumn('idFacturaAsociada');
        });
    }
}
