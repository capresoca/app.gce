<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPlanadquiFieldInCeProcontractualesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ce_procontractuales', function (Blueprint $table) {
            DB::statement("ALTER TABLE `ce_procontractuales`
	                        CHANGE COLUMN `ce_detplanadquisicione_id` `ce_detplanadquisicione_id` INT(11) NULL AFTER `pg_uniapoyo_id`;");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ce_procontractuales', function (Blueprint $table) {
            DB::statement("ALTER TABLE `ce_procontractuales`
	                        CHANGE COLUMN `ce_detplanadquisicione_id` `ce_detplanadquisicione_id` INT(11) NOT NULL AFTER `pg_uniapoyo_id`;");
        });
    }
}
