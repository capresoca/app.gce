<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPrRubroIdFieldInPrDetstringresosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE pr_detstringresos
                            DROP FOREIGN KEY pr_detstringresos_pr_rubro_id_foreign");
        DB::statement("ALTER TABLE pr_detstringresos 
                            CHANGE COLUMN pr_rubro_id pr_rubro_id INT(10),
                            DROP INDEX pr_detstringresos_pr_rubro_id_foreign");

        DB::statement("ALTER TABLE `pr_detstringresos` 
                            CHANGE COLUMN `pr_rubro_id` `pr_rubro_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
                            ADD INDEX `pr_detstringresos_pr_rubro_id_foreign_idx` (`pr_rubro_id` ASC) VISIBLE");
        DB::statement("ALTER TABLE `pr_detstringresos` 
                            ADD CONSTRAINT `pr_detstringresos_pr_rubro_id_foreign`
                              FOREIGN KEY (`pr_rubro_id`)
                              REFERENCES `pr_conceptos` (`id`)
                              ON DELETE NO ACTION
                              ON UPDATE NO ACTION");
    }

}
