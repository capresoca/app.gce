<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFieldOriAtInAuAnexot3sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `au_anexot3s`
                        CHANGE COLUMN `oriAut` `au_origenaut_id` INT(11) UNSIGNED NOT NULL COMMENT 'Origen de la autorización' ,
                        ADD INDEX `au_anexot3s_au_origenaaut_id_foreign_idx` (`au_origenaut_id` ASC)");
        DB::statement("ALTER TABLE `au_anexot3s`
                            ADD CONSTRAINT `au_anexot3s_au_origenaaut_id_foreign`
                              FOREIGN KEY (`au_origenaut_id`)
                              REFERENCES `au_origen_atencions` (`id`)
                              ON DELETE RESTRICT
                              ON UPDATE NO ACTION");
    }
}
