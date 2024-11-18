<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddField1InPrSolicitudCpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `pr_solicitud_cps`
                    DROP FOREIGN KEY `pr_solicitud_cps_pr_detstrgasto_id_foreign`,
                    DROP FOREIGN KEY `pr_solicitud_cps_au_incapacidad_id_foreign`");
        DB::statement("
            ALTER TABLE `pr_solicitud_cps` 
            DROP COLUMN `au_incapacidad_id`,
            ADD COLUMN `objeto_acontratar` LONGTEXT NOT NULL AFTER `pr_detstrgasto_id`,
            ADD COLUMN `valor` DOUBLE NULL DEFAULT NULL AFTER `objeto_acontratar`,
            DROP INDEX `pr_solicitud_cps_au_incapacidad_id_foreign_idx` ,
            DROP INDEX `pr_solicitud_cps_pr_detstrgasto_id_foreign`
        ");
    }
}
