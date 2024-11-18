<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEnumItemCeProconminutas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `ce_proconminutas` 
                                CHANGE COLUMN `estado` `estado` ENUM('Registrado', 'Legalizado', 'Activo', 'Suspendido', 'Liquidado', 'Terminado','Confirmado') 
                                CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_ci' NULL DEFAULT NULL COMMENT 'Estado actual del contrato' ");

        DB::statement("ALTER TABLE `ce_proconminutas` 
                                CHANGE COLUMN `minuta` `minuta` LONGTEXT CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_ci' NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
