<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RefactorTo1CmConmaternosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE `cm_conmaternos` ALTER `tipo_parto` DROP DEFAULT');
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE `cm_conmaternos`
	CHANGE COLUMN `tipo_parto` `tipo_parto` ENUM(\'Único\',\'Múltiple\') NOT NULL COLLATE \'utf8mb4_unicode_ci\' AFTER `fecha_parto`,
	CHANGE COLUMN `via_parto` `via_parto` ENUM(\'Vaginal\',\'Cesárea\') NULL DEFAULT NULL COLLATE \'utf8mb4_unicode_ci\' AFTER `tipo_parto`');
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cm_conmaternos', function (Blueprint $table) {
            //
        });
    }
}
