<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterField3InCmRadicadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `cm_facturas` 
        CHANGE COLUMN `estado` `estado` VARCHAR(15) CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_ci' NOT NULL DEFAULT 'Radicada'");
        DB::statement("ALTER TABLE `cm_radicados` 
                              CHANGE COLUMN `estado` `estado` ENUM('Radicado', 'En Proceso', 'Finalizado', 'Anulado') CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_ci' NULL DEFAULT NULL");
    }

}
