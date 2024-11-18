<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddField2InPrStringresosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
        ALTER TABLE `pr_stringresos` 
        ADD COLUMN `pr_concepto_id` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `fecha_confirmacion`,
        ADD INDEX `pr_stringresos_pr_concepto_id_foreign_idx` (`pr_concepto_id` ASC) VISIBLE");
        DB::statement("ALTER TABLE `pr_stringresos` 
                            ADD CONSTRAINT `pr_stringresos_pr_concepto_id_foreign`
                              FOREIGN KEY (`pr_concepto_id`)
                              REFERENCES `pr_conceptos` (`id`)
                              ON DELETE NO ACTION
                              ON UPDATE NO ACTION");
    }
}
