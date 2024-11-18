<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFieldsThingsInDetobligacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `pr_detobligaciones` 
                            DROP FOREIGN KEY `pr_detobligaciones_pr_detcdp_id_foreign`,
                            DROP FOREIGN KEY `pr_detobligaciones_pr_detrp_id_foreign`,
                            DROP FOREIGN KEY `pr_detobligaciones_pr_rubro_id_foreign`,
                            DROP FOREIGN KEY `pr_detobligaciones_pr_tipo_gasto_id_foreign`");
        DB::statement("ALTER TABLE `pr_detobligaciones` 
                            ADD COLUMN `pr_rp_id` INT(11) NOT NULL AFTER `pr_cdp_id`,
                            CHANGE COLUMN `pr_tipo_gasto_id` `pr_tipo_gasto_id` INT(11) NOT NULL ,
                            CHANGE COLUMN `pr_rubro_id` `pr_rubro_id` INT(10) UNSIGNED NOT NULL ,
                            CHANGE COLUMN `pr_detrp_id` `pr_detrp_id` INT(10) UNSIGNED NOT NULL ,
                            CHANGE COLUMN `pr_cdp_id` `pr_cdp_id` INT(11) NOT NULL ,
                            ADD INDEX `pr_detobligaciones_pr_rp_id_foreign_idx` (`pr_rp_id` ASC) VISIBLE");
        DB::statement("ALTER TABLE `pr_detobligaciones` 
                            ADD CONSTRAINT `pr_detobligaciones_pr_detcdp_id_foreign`
                              FOREIGN KEY (`pr_cdp_id`)
                              REFERENCES `pr_cdps` (`id`),
                            ADD CONSTRAINT `pr_detobligaciones_pr_detrp_id_foreign`
                              FOREIGN KEY (`pr_detrp_id`)
                              REFERENCES `pr_detrps` (`id`),
                            ADD CONSTRAINT `pr_detobligaciones_pr_rubro_id_foreign`
                              FOREIGN KEY (`pr_rubro_id`)
                              REFERENCES `pr_conceptos` (`id`),
                            ADD CONSTRAINT `pr_detobligaciones_pr_tipo_gasto_id_foreign`
                              FOREIGN KEY (`pr_tipo_gasto_id`)
                              REFERENCES `pr_tipos_gastos` (`id`),
                            ADD CONSTRAINT `pr_detobligaciones_pr_rp_id_foreign`
                              FOREIGN KEY (`pr_rp_id`)
                              REFERENCES `pr_rps` (`id`)
                              ON DELETE NO ACTION
                              ON UPDATE NO ACTION;");
    }
}
