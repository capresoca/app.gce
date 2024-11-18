<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItemsToCmConeventosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE `cm_coneventos` ALTER `tipo` DROP DEFAULT');
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE `cm_coneventos` CHANGE COLUMN `tipo` `tipo` ENUM(\'Evento Adverso - Incidente\',\'Falla\',\'Fuga\') NOT NULL COLLATE \'utf8mb4_unicode_ci\' AFTER `cm_convisita_id`');
        Schema::table('cm_coneventos', function (Blueprint $table) {
            $table->integer('cm_eventoadversoeps_id')->unsigned()->nullable();
            $table->integer('cm_eventoadversoips_id')->unsigned()->nullable();
            $table->dateTime('fecha_notificacion')->nullable();
            $table->integer('cm_manglosa_id')->nullable();
            $table->integer('historia_clinica_id')->nullable();

            $table->foreign('cm_eventoadversoeps_id')->references('id')->on('cm_eventoadversoepss')->onUpdate('no action')->onDelete('restrict');
            $table->foreign('cm_eventoadversoips_id')->references('id')->on('cm_eventoadversoipss')->onUpdate('no action')->onDelete('restrict');
            $table->foreign('cm_manglosa_id')->references('id')->on('cm_manglosas')->onUpdate('no action')->onDelete('restrict');
            $table->foreign('historia_clinica_id')->references('id')->on('gn_archivos')->onDelete('no action')->onUpdate('restrict');
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
