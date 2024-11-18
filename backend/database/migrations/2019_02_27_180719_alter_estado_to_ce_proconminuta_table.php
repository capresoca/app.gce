<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEstadoToCeProconminutaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            ALTER TABLE `ce_proconminutas`
	        CHANGE COLUMN `estado` `estado` ENUM('Registrado','Legalizado','Activo','Suspendido','Liquidado','Terminado') 
  	        NULL DEFAULT NULL COMMENT 'Estado actual del contrato' COLLATE 'utf8mb4_unicode_ci' AFTER `consecutivo`;
        ");

        DB::statement("
            ALTER TABLE `ce_proconminutas`
	        CHANGE COLUMN `consecutivo` `consecutivo` INT NULL DEFAULT NULL COMMENT 'Consecutivo del Contrato' COLLATE 'utf8mb4_unicode_ci' AFTER `ce_procontractuale_id`;
        ");
    }

}
