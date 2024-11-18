<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDataInCmCongestionriesgosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `cm_congestionriesgos` 
        CHANGE COLUMN `ruta_atencion` `ruta_atencion` ENUM('PROMOCIÓN Y MANTENIMIENTO DE LA SALUD', 'SPA Y SALUD MENTAL', 'MATERNO PERITONAL', 'CÁNCER', 'CEREBRO BASCULAR', 'CEREBRO VASCULAR') CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_ci' NULL DEFAULT NULL");
    }

}
