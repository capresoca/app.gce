<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFieldAcAuditorNullInCmGlosasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `cm_glosas` 
                            DROP FOREIGN KEY `cm_facservicios_glosas_ac_auditor_id_foreign`");
        DB::statement("ALTER TABLE `cm_glosas` 
                            CHANGE COLUMN `ac_auditor_id` `ac_auditor_id` INT(10) UNSIGNED NULL DEFAULT NULL");
        DB::statement("ALTER TABLE `cm_glosas` 
                            ADD CONSTRAINT `cm_facservicios_glosas_ac_auditor_id_foreign`
                              FOREIGN KEY (`ac_auditor_id`)
                              REFERENCES `ac_auditores` (`id`)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cm_glosas', function (Blueprint $table) {
            //
        });
    }
}
