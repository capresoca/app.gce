<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEstadoTsCompegresosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `ts_compegresos` 
                        ADD COLUMN `estado` ENUM('Registrado', 'Confirmado', 'Anulado') NULL DEFAULT 'Registrado' AFTER `updated_at`");
    }
}
