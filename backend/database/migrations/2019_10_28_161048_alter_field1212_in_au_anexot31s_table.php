<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterField1212InAuAnexot31sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `au_anexot31s` 
                    CHANGE COLUMN `valor` `valor` DOUBLE(12,2) NULL DEFAULT NULL COMMENT 'valor autorizacion' ,
                    CHANGE COLUMN `copago` `copago` DOUBLE(8,2) NULL DEFAULT '0.00' COMMENT 'copago'");
    }
}
