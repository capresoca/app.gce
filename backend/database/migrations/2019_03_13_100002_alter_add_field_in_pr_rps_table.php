<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddFieldInPrRpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `pr_rps` 
                            ADD COLUMN `pr_entidad_resolucion_id` INT(11) NULL DEFAULT NULL AFTER `gs_usuario_id`,
                            ADD INDEX `fk_pr_entidad_resoluciones_idx` (`pr_entidad_resolucion_id` ASC) VISIBLE");
        DB::statement("ALTER TABLE `pr_rps` 
                            ADD CONSTRAINT `fk_pr_entidad_resoluciones`
                              FOREIGN KEY (`pr_entidad_resolucion_id`)
                              REFERENCES `pr_entidad_resoluciones` (`id`)
                              ON DELETE NO ACTION
                              ON UPDATE NO ACTION");
    }
}
