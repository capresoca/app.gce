<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterField3InPrCdpsTabler extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `pr_cdps` 
                        ADD COLUMN `periodo` VARCHAR(4) NULL DEFAULT NULL AFTER `id`");
    }
}
