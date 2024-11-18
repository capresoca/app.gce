<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFieldsInPrCdspsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `pr_cdps` 
                CHANGE COLUMN `fecha` `fecha` DATE NULL DEFAULT NULL ,
                CHANGE COLUMN `fecha_vence` `fecha_vence` DATE NULL DEFAULT NULL");
    }
}
