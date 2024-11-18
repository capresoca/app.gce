<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RefactorToRsAfiliadoServiciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rs_afiliado_servicios', function (Blueprint $table) {
            $table->integer('rs_servhabilitado_id')->nullable();
            $table->foreign('rs_servhabilitado_id')->references('id')->on('rs_servhabilitados');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rs_afiliado_servicios', function (Blueprint $table) {
            $table->dropForeign('rs_afiliado_servicios_rs_servhabilitado_id_foreign');
            $table->dropColumn('rs_servhabilitado_id');
        });
    }
}
