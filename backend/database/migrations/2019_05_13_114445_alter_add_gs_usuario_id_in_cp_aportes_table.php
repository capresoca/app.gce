<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddGsUsuarioIdInCpAportesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `cp_aportes` 
                            ADD COLUMN `gs_usuario_id` INT(10) NULL DEFAULT NULL AFTER `tipo_cotizante`,
                            ADD INDEX `FK_cp_gs_usuarios_idx` (`gs_usuario_id` ASC) VISIBLE');
        DB::statement('ALTER TABLE `cp_aportes` 
                            ADD CONSTRAINT `FK_cp_gs_usuarios`
                              FOREIGN KEY (`gs_usuario_id`)
                              REFERENCES `gs_usuarios` (`id`)
                              ON DELETE NO ACTION
                              ON UPDATE NO ACTION');
    }
}
