<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCampFieldInCpAportesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `cp_aportes` 
        ADD COLUMN `observacion` LONGTEXT NULL DEFAULT NULL AFTER `ingreso`,
        ADD COLUMN `novedad` TINYINT NULL DEFAULT 0 AFTER `observacion`");
    }
}
