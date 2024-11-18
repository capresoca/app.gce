<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddForeignTCmGlosasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `cm_glosas` 
                            ADD COLUMN `ce_proconminutaupcservicio_id` INT(11) NULL DEFAULT NULL AFTER `of_facservicio`,
                            ADD INDEX `cm_facservicios_glosas_ce_proconminutaupcservicio_id_idx` (`ce_proconminutaupcservicio_id` ASC)");
        DB::statement("ALTER TABLE `cm_glosas` 
                            ADD CONSTRAINT `cm_facservicios_glosas_ce_proconminutaupcservicio_id`
                              FOREIGN KEY (`ce_proconminutaupcservicio_id`)
                              REFERENCES `ce_proconminutaupcservicios` (`id`)
                              ON DELETE RESTRICT
                              ON UPDATE NO ACTION");
    }
}
