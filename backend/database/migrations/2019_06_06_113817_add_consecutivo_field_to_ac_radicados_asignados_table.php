<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConsecutivoFieldToAcRadicadosAsignadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ac_radicados_asignados', function (Blueprint $table) {
            $table->bigInteger('consecutivo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ac_radicados_asignados', function (Blueprint $table) {
            $table->dropColumn('consecutivo');
        });
    }
}
