<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFieldEstadoInCmAuditorConcurrenciaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `cm_auditor_concurrencia` 
                    ADD COLUMN `estado` ENUM('Activo', 'Inactivo') NULL DEFAULT 'Activo' AFTER `user_id`");
    }
}
