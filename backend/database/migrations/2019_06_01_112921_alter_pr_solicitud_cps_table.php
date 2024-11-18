<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPrSolicitudCpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pr_solicitud_cps', function (Blueprint $table) {
            $table->enum('tipo',['Incapacidad','Contrato'])->default('Incapacidad');

        });

        DB::statement("ALTER TABLE `pr_solicitud_cps`
	CHANGE COLUMN `pr_detstrgasto_id` `pr_detstrgasto_id` INT(10) UNSIGNED NULL AFTER `fecha`;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pr_solicitud_cps', function (Blueprint $table) {
            $table->dropColumn('tipo');

        });
    }
}
