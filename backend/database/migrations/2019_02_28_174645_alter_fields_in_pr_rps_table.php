<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFieldsInPrRpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `pr_rps` DROP FOREIGN KEY `fk_cdp_rp`");
        DB::statement("ALTER TABLE `pr_rps` 
                            ADD COLUMN `periodo` VARCHAR(4) NULL AFTER `id`,
                            ADD COLUMN `valor_compromiso` DOUBLE NULL AFTER `fecha_vence`,
                            ADD COLUMN `ce_proconminuta_id` INT(11) NULL DEFAULT NULL AFTER `pr_cdp_id`,
                            ADD COLUMN `pr_strgasto_id` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `ce_proconminuta_id`,
                            ADD COLUMN `afectar_cdp_xcompra` ENUM('Si', 'No') NULL DEFAULT 'No' AFTER `gs_usuario_id`,
                            CHANGE COLUMN `pr_cdp_id` `pr_cdp_id` INT(11) NULL DEFAULT NULL AFTER `valor_compromiso`,
                            CHANGE COLUMN `consecutivo` `consecutivo` INT(11) NOT NULL ,
                            CHANGE COLUMN `fecha_vence` `fecha_vence` DATETIME NULL DEFAULT NULL ,
                            ADD INDEX `fk_ce_proconminutas_idx` (`ce_proconminuta_id` ASC) VISIBLE,
                            ADD INDEX `fk_pr_strgastos_idx` (`pr_strgasto_id` ASC) VISIBLE");
        DB::statement("ALTER TABLE `pr_rps` 
                            ADD CONSTRAINT `fk_cdp_rp`
                              FOREIGN KEY (`pr_cdp_id`)
                              REFERENCES `pr_cdps` (`id`),
                            ADD CONSTRAINT `fk_ce_proconminutas`
                              FOREIGN KEY (`ce_proconminuta_id`)
                              REFERENCES `ce_proconminutas` (`id`)
                              ON DELETE NO ACTION
                              ON UPDATE NO ACTION,
                            ADD CONSTRAINT `fk_pr_strgastos`
                              FOREIGN KEY (`pr_strgasto_id`)
                              REFERENCES `pr_strgastos` (`id`)
                              ON DELETE NO ACTION
                              ON UPDATE NO ACTION");
    }

}
