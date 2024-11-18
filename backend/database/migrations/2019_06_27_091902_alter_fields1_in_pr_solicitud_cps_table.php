<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFields1InPrSolicitudCpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `pr_solicitud_cps` 
                            ADD COLUMN `desc_necesidad` LONGTEXT NULL DEFAULT NULL AFTER `objeto_acontratar`,
                            ADD COLUMN `ce_proconestudioprevio_id` INT(11) NULL DEFAULT NULL AFTER `valor`,
                            ADD COLUMN `incapacidad_id` INT(11) NULL DEFAULT NULL AFTER `ce_proconestudioprevio_id`,
                            CHANGE COLUMN `tipo` `tipo` ENUM('Incapacidad', 'Contrato') NOT NULL DEFAULT 'Incapacidad' AFTER `incapacidad_id`,
                            ADD INDEX `pr_solicitud_cpd_incapacidad_id_foreign_idx` (`incapacidad_id` ASC),
                            ADD INDEX `pr_solicitud_cdp_ce_proconestudio_id_foreign_idx` (`ce_proconestudioprevio_id` ASC)");
        DB::statement("ALTER TABLE `pr_solicitud_cps` 
                        ADD CONSTRAINT `pr_solicitud_cpd_incapacidad_id_foreign`
                          FOREIGN KEY (`incapacidad_id`)
                          REFERENCES `au_incapacidades` (`id`)
                          ON DELETE RESTRICT
                          ON UPDATE NO ACTION,
                        ADD CONSTRAINT `pr_solicitud_cdp_ce_proconestudio_id_foreign`
                          FOREIGN KEY (`ce_proconestudioprevio_id`)
                          REFERENCES `ce_proconestprevios` (`id`)
                          ON DELETE RESTRICT
                          ON UPDATE NO ACTION");
    }

}
