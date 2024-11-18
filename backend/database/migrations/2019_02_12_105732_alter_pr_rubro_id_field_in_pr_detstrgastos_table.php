<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPrRubroIdFieldInPrDetstrgastosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE pr_detstrgastos
                            DROP FOREIGN KEY pr_detstrgastos_pr_rubro_id_foreign");
        DB::statement("ALTER TABLE pr_detstrgastos 
                            CHANGE COLUMN pr_rubro_id pr_rubro_id INT(10),
                            DROP INDEX pr_detstrgastos_pr_rubro_id_foreign");
        DB::statement("ALTER TABLE `pr_detstrgastos` 
                            CHANGE COLUMN `pr_rubro_id` `pr_rubro_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
                            ADD INDEX `pr_detstrgastos_pr_rubro_id_foreign_idx` (`pr_rubro_id` ASC) VISIBLE");
        DB::statement("ALTER TABLE `pr_detstrgastos` 
                            ADD CONSTRAINT `pr_detstrgastos_pr_rubro_id_foreign`
                              FOREIGN KEY (`pr_rubro_id`)
                              REFERENCES `pr_conceptos` (`id`)
                              ON DELETE NO ACTION
                              ON UPDATE NO ACTION");

    }
}
