<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFieldInPrDetcdpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `pr_detcdps` 
                        CHANGE COLUMN `valor_ejecutado` `valor_ejecutado` DOUBLE NULL DEFAULT 0");
    }
}
