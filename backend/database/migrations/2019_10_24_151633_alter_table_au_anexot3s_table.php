<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableAuAnexot3sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `au_anexot3s` 
                            CHANGE COLUMN `prior` `prior` TINYINT NOT NULL DEFAULT 0 COMMENT 'Si/No Prioritario (1 = Priorinario / 0 = No Prioritario)'");
    }
}
