<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFinalidadesProcedimientoFieldInCmFacserviciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cm_facservicios', function (Blueprint $table) {
            $table->enum('finalidad_procedimiento',
                [
                    'Diagnóstico',
                    'Terapéutico',
                    'Protección específica',
                    'Detección temprana de enfermedad general',
                    'Detección temprana de enfermedad laboral'
                ])->nullable();

            $table->string('complicacion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cm_facservicios', function (Blueprint $table) {
            $table->dropColumn('finalidad_procedimiento');
            $table->dropColumn('complicacion');
        });
    }
}
