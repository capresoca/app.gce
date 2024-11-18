<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFieldFechaActualizacionInCmFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `cm_facturas` 
                            ADD COLUMN `feha_repcapita` DATETIME NULL DEFAULT NULL AFTER `gs_usuario_avala_id`");
        DB::statement("ALTER TABLE `cm_facturas` 
                            ADD COLUMN `gs_usuario_capita` INT(11) NULL DEFAULT NULL AFTER `feha_repcapita`");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `cm_facturas` 
                            DROP COLUMN `feha_repcapita`");
        DB::statement("ALTER TABLE `cm_facturas` 
                            DROP COLUMN `gs_usuario_capita`");
    }
}
