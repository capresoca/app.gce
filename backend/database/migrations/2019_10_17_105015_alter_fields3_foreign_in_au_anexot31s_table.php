<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFields3ForeignInAuAnexot31sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `au_anexot3s` 
                            DROP FOREIGN KEY `au_anexot3s_usureg_foreign`");
        DB::statement("ALTER TABLE `au_anexot3s` 
                            CHANGE COLUMN `usuReg` `gs_usuariovalida_id` INT(11) NULL DEFAULT NULL ,
                            DROP INDEX `au_anexot3s_usureg_foreign` ,
                            ADD INDEX `au_anexot3s_usureg_foreign_idx` (`gs_usuariovalida_id` ASC)");
        DB::statement("ALTER TABLE `au_anexot3s` 
                            ADD CONSTRAINT `au_anexot3s_usureg_foreign`
                              FOREIGN KEY (`gs_usuariovalida_id`)
                              REFERENCES `gs_usuarios` (`id`)");
    }
}
