<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFieldInAcAuditoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ac_auditores', function (Blueprint $table) {
            DB::statement("ALTER TABLE `ac_auditores`
                                ADD COLUMN `estado` TINYINT NULL DEFAULT 1 AFTER `tipo`,
                                ADD COLUMN `auditor_concurrente` TINYINT NULL DEFAULT 0 AFTER `estado`;");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ac_auditores', function (Blueprint $table) {
            $table->dropColumn('estado');
            $table->dropColumn('auditor_concurrente');
        });
    }
}
