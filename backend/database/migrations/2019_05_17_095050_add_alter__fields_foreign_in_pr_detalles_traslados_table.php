<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAlterFieldsForeignInPrDetallesTrasladosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `pr_detalles_traslados`
                            ADD COLUMN `pr_detstrgastodebitado_id` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `pr_detstrgasto_id`,
                            ADD COLUMN `pr_detstringresodebitado_id` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `pr_detstrgastodebitado_id`,
                            ADD INDEX `pr_detalles_traslados_pr_detstrgastodebitado_id_foreidn_idx` (`pr_detstrgastodebitado_id` ASC) VISIBLE,
                            ADD INDEX `pr_detalles_traslados_pr_detstringresodebitado_id_foreidn_idx` (`pr_detstringresodebitado_id` ASC) VISIBLE");
        DB::statement("ALTER TABLE `pr_detalles_traslados`
                            ADD CONSTRAINT `pr_detalles_traslados_pr_detstrgastodebitado_id_foreidn`
                              FOREIGN KEY (`pr_detstrgastodebitado_id`)
                              REFERENCES `pr_detstrgastos` (`id`)
                              ON DELETE NO ACTION
                              ON UPDATE NO ACTION,
                            ADD CONSTRAINT `pr_detalles_traslados_pr_detstringresodebitado_id_foreidn`
                              FOREIGN KEY (`pr_detstringresodebitado_id`)
                              REFERENCES `pr_detstringresos` (`id`)
                              ON DELETE NO ACTION
                              ON UPDATE NO ACTION");
    }

}
