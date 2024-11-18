<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddFieldInPrDetrpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `pr_detrps` 
                            ADD COLUMN `pr_tipo_gasto_id` INT(11) NULL DEFAULT NULL AFTER `pr_detcdp_id`,
                            ADD INDEX `pr_detrps_pr_tipo_gasto_id_foreign_idx` (`pr_tipo_gasto_id` ASC) VISIBLE");
        DB::statement("ALTER TABLE `pr_detrps` 
                            ADD CONSTRAINT `pr_detrps_pr_tipo_gasto_id_foreign`
                              FOREIGN KEY (`pr_tipo_gasto_id`)
                              REFERENCES `pr_tipos_gastos` (`id`)
                              ON DELETE NO ACTION
                              ON UPDATE NO ACTION");
    }
}
