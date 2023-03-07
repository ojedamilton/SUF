<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddNumfacturaFacturaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->string('numeroFactura',6)->nullable()->after('id');
        });
        // Agrego el zerofill para que grabe los ceros a la izquierda (000001)
        DB::statement('ALTER TABLE facturas MODIFY numeroFactura INT(6) ZEROFILL NULL');
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->dropColumn('numeroFactura');
        });
    }
}
