<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldEstadoServicioInCmFacserviciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cm_facservicios', function (Blueprint $table) {
            $table->boolean('avalado')->nullable()->comment('Campo que verifica si el servicio fue avalado.');
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
            $table->dropColumn('avalado');
        });
    }
}
