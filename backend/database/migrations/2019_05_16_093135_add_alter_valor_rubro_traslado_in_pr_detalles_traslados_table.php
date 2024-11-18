<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAlterValorRubroTrasladoInPrDetallesTrasladosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `pr_detalles_traslados`
                      ADD COLUMN `valor_rubro_traslado` DOUBLE NOT NULL DEFAULT 0 AFTER `valor_movimiento`;");
    }

}
