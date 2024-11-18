<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAsSoltrasladosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `as_soltraslados` ALTER `as_bduaproceso_id` DROP DEFAULT, ALTER `gn_archivo_id` DROP DEFAULT;");
        DB::statement("ALTER TABLE `as_soltraslados` CHANGE COLUMN `as_bduaproceso_id` `as_bduaproceso_id` INT(11) NULL AFTER `id`, CHANGE COLUMN `gn_archivo_id` `gn_archivo_id` INT(11) NULL AFTER `as_bduaproceso_id`;");

        Schema::table('as_soltraslados', function (Blueprint $table) {
            $table->unsignedInteger('as_svid_id')->index()->nullable();
        });
    }

}
