<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldInAuIncapacidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `au_incapacidades` 
                           ADD COLUMN `pr_rubro_id` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `dias_pagado`,
                           ADD INDEX `fk_au_incapacidades_pr_conceptos_idx` (`pr_rubro_id` ASC) VISIBLE");
        DB::statement("ALTER TABLE `au_incapacidades` 
                                ADD CONSTRAINT `fk_au_incapacidades_pr_conceptos`
                                  FOREIGN KEY (`pr_rubro_id`)
                                  REFERENCES `pr_conceptos` (`id`)
                                  ON DELETE NO ACTION
                                  ON UPDATE NO ACTION");
    }
}
