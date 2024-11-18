<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddForzarRetiroInCpAportesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `cp_aportes` 
                        ADD COLUMN `forzar_retiro` TINYINT NULL DEFAULT 0 AFTER `novedad`");
    }

}
