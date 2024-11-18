<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterField123InAuAnexot3sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `au_anexot3s` 
                CHANGE COLUMN `fS1` `fS1` DATETIME NULL DEFAULT NULL COMMENT 'Fecha de envió al prestador'");
    }
}
