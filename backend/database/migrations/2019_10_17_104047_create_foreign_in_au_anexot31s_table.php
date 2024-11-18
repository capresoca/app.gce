<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForeignInAuAnexot31sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `au_anexot31s` 
                            ADD INDEX `au_anexot31s_au_anexot3s_id_foreign_idx` (`au_anexot3s_id` ASC)");
        DB::statement("ALTER TABLE `au_anexot31s` 
                            ADD CONSTRAINT `au_anexot31s_au_anexot3s_id_foreign`
                            FOREIGN KEY (`au_anexot3s_id`)
                            REFERENCES `au_anexot3s` (`id`)
                            ON DELETE RESTRICT
                            ON UPDATE NO ACTION");
    }
}
