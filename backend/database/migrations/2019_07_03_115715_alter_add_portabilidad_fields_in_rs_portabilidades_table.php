<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddPortabilidadFieldsInRsPortabilidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `rs_portabilidades` 
                        ADD COLUMN `email` VARCHAR(100) NULL DEFAULT NULL AFTER `estado`,
                        ADD COLUMN `motivo` LONGTEXT NULL DEFAULT NULL AFTER `email`");
    }
}
