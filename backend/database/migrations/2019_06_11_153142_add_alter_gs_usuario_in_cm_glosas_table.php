<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAlterGsUsuarioInCmGlosasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `cm_glosas` 
                            ADD COLUMN `gs_usuario_id` INT(11) NULL DEFAULT NULL AFTER `observacion`,
                            ADD INDEX `cm_facservicios_glosas_gs_usuario_id_foreign_idx` (`gs_usuario_id` ASC)");
        DB::statement("ALTER TABLE `cm_glosas` 
                            ADD CONSTRAINT `cm_facservicios_glosas_gs_usuario_id_foreign`
                              FOREIGN KEY (`gs_usuario_id`)
                              REFERENCES `gs_usuarios` (`id`)
                              ON DELETE RESTRICT
                              ON UPDATE NO ACTION");
    }
}
