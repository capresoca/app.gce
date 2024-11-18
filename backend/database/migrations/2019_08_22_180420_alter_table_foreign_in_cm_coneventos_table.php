<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableForeignInCmConeventosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        DB::statement('ALTER TABLE `cm_coneventos` ALTER `cm_convisita_id` DROP DEFAULT');
        DB::statement('ALTER TABLE `cm_coneventos` CHANGE COLUMN `cm_convisita_id` `cm_convisita_id` INT(11) NULL AFTER `id`');
        Schema::table('cm_coneventos', function (Blueprint $table) {
            $table->integer('cm_concurrencia_id')->nullable();
            $table->foreign('cm_concurrencia_id')->references('id')->on('cm_concurrencias')->onDelete('restrict')->onUpdate('no action');
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
        Schema::table('cm_coneventos', function (Blueprint $table) {
            //
        });
    }
}
