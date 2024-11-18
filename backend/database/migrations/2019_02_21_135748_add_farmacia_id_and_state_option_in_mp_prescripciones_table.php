<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFarmaciaIdAndStateOptionInMpPrescripcionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `mp_prescripciones`
	    CHANGE COLUMN `estado` `estado` ENUM('Revisión','Aprobado','Anulado','Duplicado') NULL DEFAULT 'Revisión' COLLATE 'utf8mb4_unicode_ci' AFTER `EstPres`;");
        Schema::table('mp_prescripciones', function (Blueprint $table) {
            $table->integer('farmacia_id')->nullable();
            $table->text('observaciones')->nullable();
            $table->foreign('farmacia_id')->references('id')->on('rs_entidades')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::table('mp_prescripciones', function (Blueprint $table) {
            $table->dropForeign('mp_prescripciones_farmacia_id_foreign');
            $table->dropColumn('observaciones');
            $table->dropColumn('farmacia_id');
        });
    }

}
