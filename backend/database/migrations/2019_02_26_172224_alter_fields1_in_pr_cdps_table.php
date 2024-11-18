<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFields1InPrCdpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `pr_cdps` 
                            ADD COLUMN `pr_dependencia_id` INT(11) NULL DEFAULT NULL AFTER `objecto`,
                            ADD COLUMN `pr_entidadresolucion_id` INT(11) NULL AFTER `pr_dependencia_id`,
                            ADD COLUMN `gs_usuario_id` INT(10) NULL AFTER `pr_entidadresolucion_id`,
                            ADD COLUMN `gs_usuario_conf_id` INT(10) NULL DEFAULT NULL AFTER `gs_usuario_id`,
                            ADD COLUMN `valor_cdp` DOUBLE NULL DEFAULT NULL AFTER `gs_usuario_conf_id`,
                            ADD COLUMN `estado` ENUM('Registrada', 'Confirmada', 'Anulada') NULL DEFAULT 'Registrada' AFTER `valor_cdp`,
                            ADD COLUMN `fecha_confirmacion` DATETIME NULL DEFAULT NULL AFTER `estado`,
                            ADD INDEX `pr_cdps_in_pr_dependencia_id_forein_idx` (`pr_dependencia_id` ASC) VISIBLE,
                            ADD INDEX `pr_cdps_in_pr_entidad_resoluciones_id_forein_idx` (`pr_entidadresolucion_id` ASC) VISIBLE,
                            ADD INDEX `pr_cdps_in_gs_usuario_id_forein_idx` (`gs_usuario_id` ASC) VISIBLE,
                            ADD INDEX `pr_cdps_in_gs_usuario_conf_id_forein_idx` (`gs_usuario_conf_id` ASC) VISIBLE");
        DB::statement("ALTER TABLE `pr_cdps` 
                            ADD CONSTRAINT `pr_cdps_in_pr_dependencia_id_forein`
                              FOREIGN KEY (`pr_dependencia_id`)
                              REFERENCES `pr_dependencias` (`id`)
                              ON DELETE NO ACTION
                              ON UPDATE NO ACTION,
                            ADD CONSTRAINT `pr_cdps_in_pr_entidad_resoluciones_id_forein`
                              FOREIGN KEY (`pr_entidadresolucion_id`)
                              REFERENCES `pr_entidad_resoluciones` (`id`)
                              ON DELETE NO ACTION
                              ON UPDATE NO ACTION,
                            ADD CONSTRAINT `pr_cdps_in_gs_usuario_id_forein`
                              FOREIGN KEY (`gs_usuario_id`)
                              REFERENCES `gs_usuarios` (`id`)
                              ON DELETE NO ACTION
                              ON UPDATE NO ACTION,
                            ADD CONSTRAINT `pr_cdps_in_gs_usuario_conf_id_forein`
                              FOREIGN KEY (`gs_usuario_conf_id`)
                              REFERENCES `gs_usuarios` (`id`)
                              ON DELETE NO ACTION
                              ON UPDATE NO ACTION");
    }
}
