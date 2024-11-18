<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItemCmConvisitasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE `cm_convisitas` CHANGE COLUMN `uci` `uci` TINYINT(1) NULL DEFAULT \'0\' COLLATE \'utf8mb4_unicode_ci\' AFTER `glasgow`');
        Schema::table('cm_convisitas', function (Blueprint $table) {
            // $table->boolean('ventilacion_asistido')->nullable()->default(false);
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cm_convisitas', function (Blueprint $table) {
            //
        });
    }
}
