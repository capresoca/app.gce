<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlterFieldAuNexot31sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `au_anexot31s` 
                            ADD COLUMN `gs_usuariovalida_id` INT(10) NULL DEFAULT NULL AFTER `imp`,
                            ADD INDEX `au_anexot31s_gs_usuariovalida_foreign_idx` (`gs_usuariovalida_id` ASC)");
        DB::statement("ALTER TABLE `au_anexot31s` 
                            ADD CONSTRAINT `au_anexot31s_gs_usuariovalida_foreign`
                              FOREIGN KEY (`gs_usuariovalida_id`)
                              REFERENCES `gs_usuarios` (`id`)
                              ON DELETE RESTRICT
                              ON UPDATE NO ACTION");
    }
}
