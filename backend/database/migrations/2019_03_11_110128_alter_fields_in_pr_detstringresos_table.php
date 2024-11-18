<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFieldsInPrDetstringresosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `pr_detstringresos` 
                            CHANGE COLUMN `valor_inicial` `valor_inicial` DOUBLE NOT NULL DEFAULT 0 ,
                            CHANGE COLUMN `valor_credito` `valor_credito` DOUBLE NULL DEFAULT 0 ,
                            CHANGE COLUMN `valor_debito` `valor_debito` DOUBLE NULL DEFAULT 0 ,
                            CHANGE COLUMN `valor_ejecutado` `valor_ejecutado` DOUBLE NULL DEFAULT 0 ,
                            CHANGE COLUMN `valor_recaudo` `valor_recaudo` DOUBLE NULL DEFAULT 0 ");
    }
}
