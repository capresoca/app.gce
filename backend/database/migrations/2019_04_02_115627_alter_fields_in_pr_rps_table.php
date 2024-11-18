<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFieldsInPrRpsTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `pr_rps` 
                            CHANGE COLUMN `fecha` `fecha` DATE NOT NULL ,
                            CHANGE COLUMN `fecha_vence` `fecha_vence` DATE NULL DEFAULT NULL ;
                            ");
    }
}
