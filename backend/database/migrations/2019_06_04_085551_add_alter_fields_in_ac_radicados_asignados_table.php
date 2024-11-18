<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAlterFieldsInAcRadicadosAsignadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `ac_radicados_asignados` 
                                            ADD COLUMN `ac_auditor_id` INT(10) UNSIGNED NULL AFTER `id`,
                                            ADD COLUMN `cm_radicado_id` INT(11) NULL AFTER `ac_auditor_id`,
                                            ADD COLUMN `tipo` ENUM('Primera vez', 'Segunda vez') NULL AFTER `cm_radicado_id`,
                                            ADD INDEX `fk_cd_auditores_in_ac_radicados_asignados_idx` (`ac_auditor_id` ASC),
                                            ADD INDEX `fk_cm_radicados_in_ac_radicados_asignados_idx` (`cm_radicado_id` ASC)");
        DB::statement("ALTER TABLE `ac_radicados_asignados` 
                                ADD CONSTRAINT `fk_cd_auditores_in_ac_radicados_asignados`
                                  FOREIGN KEY (`ac_auditor_id`)
                                  REFERENCES `ac_auditores` (`id`)
                                  ON DELETE RESTRICT
                                  ON UPDATE NO ACTION,
                                ADD CONSTRAINT `fk_cm_radicados_in_ac_radicados_asignados`
                                  FOREIGN KEY (`cm_radicado_id`)
                                  REFERENCES `cm_radicados` (`id`)
                                  ON DELETE RESTRICT
                                  ON UPDATE NO ACTION;");
    }
}
