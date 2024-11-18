<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddField1InPrStrgastosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `pr_strgastos` 
                        ADD COLUMN `pr_concepto_id` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `fecha_confirmacion`,
                        ADD INDEX `pr_str_gastos_pr_concepto_id_idx` (`pr_concepto_id` ASC) VISIBLE");
        DB::statement("ALTER TABLE `pr_strgastos` 
                            ADD CONSTRAINT `pr_str_gastos_pr_concepto_id`
                              FOREIGN KEY (`pr_concepto_id`)
                              REFERENCES `pr_conceptos` (`id`)
                              ON DELETE NO ACTION
                              ON UPDATE NO ACTION");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pr_strgastos', function (Blueprint $table) {
            //
        });
    }
}
