<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsInMpPrescripcionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mp_prescripciones', function (Blueprint $table) {
            $table->string('ips',500)->nullable();
            $table->string('paciente',500)->nullable();
        });

        DB::statement("ALTER TABLE `mp_prescripciones`
	                      ADD INDEX `NoPrescripcion` (`NoPrescripcion`);
                      ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mp_prescripciones', function (Blueprint $table) {
            $table->dropColumn('ips')->nullable();
            $table->dropColumn('paciente')->nullable();
        });

        DB::statement("ALTER TABLE `mp_prescripciones`
	                            DROP INDEX `NoPrescripcion`");
    }
}
