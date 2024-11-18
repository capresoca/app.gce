<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddTecnicoInAcAuditoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `ac_auditores` 
        ADD COLUMN `tecnico` TINYINT(4) NULL DEFAULT 0 AFTER `auditor_concurrente`");
    }
}
