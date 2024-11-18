<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddEstadoValorInPrCdpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `pr_cdps` 
                            ADD COLUMN `estado_valor` ENUM('Disponible', 'Comprometido Parcialmente', 'Comprometido Total') NULL DEFAULT NULL AFTER `fecha_confirmacion`,
                            CHANGE COLUMN `fecha_anulacion` `fecha_anulacion` DATE NULL DEFAULT NULL AFTER `estado_valor`,
                            CHANGE COLUMN `concepto_anulacionint` `concepto_anulacionint` LONGTEXT CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_ci' NULL DEFAULT NULL AFTER `fecha_anulacion`");
    }
}
