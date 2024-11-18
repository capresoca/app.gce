<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFildsradInCmRadicadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `cm_radicados` 
              CHANGE COLUMN `estado` `estado` ENUM('Radicado', 'Primera Asignación', 'Segunda Asignación', 'Primer Glosado', 'Segundo Glosado', 'Respondido', 'Conciliación', 'Avalado', 'Conciliado', 'Anulado') CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_ci' NULL DEFAULT NULL");
    }
}
