<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFieldsPrModDetPrespuestalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `pr_mod_det_presupuestales` 
                            DROP FOREIGN KEY `pr_mod_det_strgastos_pr_mod_strgasto_id_foreign`");
        DB::statement("ALTER TABLE `pr_mod_det_presupuestales` 
                            ADD COLUMN `pr_detstringreso_id` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `pr_tipo_gasto_id`,
                            ADD COLUMN `pr_tipo_ingreso_id` INT(11) NULL DEFAULT NULL AFTER `pr_detstringreso_id`,
                            CHANGE COLUMN `pr_mod_strgasto_id` `pr_mod_presupuestal_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
                            ADD INDEX `pr_mod_det_strgastos_pr_stringreso_id_foreign_idx` (`pr_detstringreso_id` ASC) VISIBLE,
                            ADD INDEX `pr_mod_det_strgastos_pr_tipo_ingreso_id_foreign_idx` (`pr_tipo_ingreso_id` ASC) VISIBLE");
        DB::statement("ALTER TABLE `pr_mod_det_presupuestales` 
                            ADD CONSTRAINT `pr_mod_det_strgastos_pr_mod_strgasto_id_foreign`
                              FOREIGN KEY (`pr_mod_presupuestal_id`)
                              REFERENCES `pr_mod_presupuestales` (`id`),
                            ADD CONSTRAINT `pr_mod_det_strgastos_pr_stringreso_id_foreign`
                              FOREIGN KEY (`pr_detstringreso_id`)
                              REFERENCES `pr_detstringresos` (`id`)
                              ON DELETE NO ACTION
                              ON UPDATE NO ACTION,
                            ADD CONSTRAINT `pr_mod_det_strgastos_pr_tipo_ingreso_id_foreign`
                              FOREIGN KEY (`pr_tipo_ingreso_id`)
                              REFERENCES `pr_tipo_ingresos` (`id`)
                              ON DELETE NO ACTION
                              ON UPDATE NO ACTION");
    }
}
