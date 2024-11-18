<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldInPrSolicitudCpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `pr_solicitud_cps` 
                              ADD COLUMN `au_incapacidad_id` INT(11) NULL AFTER `pr_detstrgasto_id`,
                              ADD INDEX `pr_solicitud_cps_au_incapacidad_id_foreign_idx` (`au_incapacidad_id` ASC) VISIBLE");

        DB::statement("ALTER TABLE `pr_solicitud_cps` 
                            ADD CONSTRAINT `pr_solicitud_cps_au_incapacidad_id_foreign`
                              FOREIGN KEY (`au_incapacidad_id`)
                              REFERENCES `au_incapacidades` (`id`)
                              ON DELETE NO ACTION
                              ON UPDATE NO ACTION");
    }
}
