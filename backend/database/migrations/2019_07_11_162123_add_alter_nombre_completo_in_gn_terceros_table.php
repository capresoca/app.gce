<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAlterNombreCompletoInGnTercerosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `gn_terceros` 
                            ADD COLUMN `nombre_completo` VARCHAR(200) NULL DEFAULT NULL AFTER `naturaleza_juridica`");
    }
}
