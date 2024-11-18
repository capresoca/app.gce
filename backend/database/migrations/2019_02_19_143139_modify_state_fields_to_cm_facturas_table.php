<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyStateFieldsToCmFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `cm_facturas` CHANGE COLUMN `estado` `estado` 
                          ENUM('Radicada','Devuelta','Glosada','Respondida','Avalada','Conciliacion','Anulada') 
                          NOT NULL DEFAULT 'Radicada' COLLATE 'utf8mb4_unicode_ci' AFTER `valor_neto`;");


        Schema::table('cm_facturas', function (Blueprint $table) {
            $table->integer('instancia')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cm_facturas', function (Blueprint $table) {
            $table->dropColumn('instancia');
        });
    }
}
