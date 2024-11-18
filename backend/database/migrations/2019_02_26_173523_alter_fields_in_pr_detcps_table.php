<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFieldsInPrDetcpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `pr_detcdps` DROP FOREIGN KEY `fk_cdp_rubros`");
        DB::statement("ALTER TABLE `pr_detcdps` 
                            ADD COLUMN `pr_detstrgasto_id` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `pr_cdp_id`,
                            ADD COLUMN `pr_tipo_gasto_id` INT(11) NULL AFTER `pr_rubro_id`,
                            CHANGE COLUMN `pr_rubro_id` `pr_rubro_id` INT(10) UNSIGNED NULL ,
                            ADD INDEX `fk_cdp_tipos_gastos_idx` (`pr_tipo_gasto_id` ASC) VISIBLE,
                            ADD INDEX `f_cdp_pr_detstrgastos_idx` (`pr_detstrgasto_id` ASC) VISIBLE");
//        DB::statement("ALTER TABLE `pr_detcdps` RENAME INDEX `fk_cdp_rubros` TO `fk_cdp_rubros_idx`");
        DB::statement("ALTER TABLE `pr_detcdps` ALTER INDEX `fk_cdp_rubros_idx` VISIBLE");
        DB::statement("ALTER TABLE `pr_detcdps` 
                            ADD CONSTRAINT `fk_cdp_rubros`
                              FOREIGN KEY (`pr_rubro_id`)
                              REFERENCES `pr_conceptos` (`id`),
                            ADD CONSTRAINT `fk_cdp_tipos_gastos`
                              FOREIGN KEY (`pr_tipo_gasto_id`)
                              REFERENCES `pr_tipos_gastos` (`id`)
                              ON DELETE NO ACTION
                              ON UPDATE NO ACTION,
                            ADD CONSTRAINT `f_cdp_pr_detstrgastos`
                              FOREIGN KEY (`pr_detstrgasto_id`)
                              REFERENCES `pr_detstrgastos` (`id`)
                              ON DELETE NO ACTION
                              ON UPDATE NO ACTION");
    }

}
