<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterIntnInCpLiquidacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `capresoca`.`cp_liquidaciones` 
                    CHANGE COLUMN `dias_pagados` `dias_pagados` INT(5) NULL DEFAULT '0'");
    }
}
