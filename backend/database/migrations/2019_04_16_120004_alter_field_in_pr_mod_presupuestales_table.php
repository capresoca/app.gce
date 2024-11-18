<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFieldInPrModPresupuestalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `pr_mod_presupuestales` 
                    ADD COLUMN `tipo` ENUM('Ingreso', 'Gasto') NOT NULL AFTER `fecha_anulacion`,
                    CHANGE COLUMN `consecutivo` `consecutivo_presupuestal` INT(11) NOT NULL");
    }
}
