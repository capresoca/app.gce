<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrSolicitudIdFieldInAuIncapacidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `au_incapacidades` 
                            ADD COLUMN `pr_solicitud_cp_id` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `pr_rubro_id`,
                            ADD INDEX `fk_au_incapacidades_pr_solicitud_cps_idx` (`pr_solicitud_cp_id` ASC) VISIBLE");
        DB::statement("ALTER TABLE `au_incapacidades` 
                            ADD CONSTRAINT `fk_au_incapacidades_pr_solicitud_cps`
                              FOREIGN KEY (`pr_solicitud_cp_id`)
                              REFERENCES `pr_solicitud_cps` (`id`)
                              ON DELETE NO ACTION
                              ON UPDATE NO ACTION");
    }

}
