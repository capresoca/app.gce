<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConsecutivoFieldInAcRadicadosAsignadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ac_radicados_asignados', function (Blueprint $table) {
            $table->integer('ac_radicados_asignados')->nullable();
        });
    }

    public function down()
    {
        Schema::table('ac_radicados_asignados', function (Blueprint $table) {
            $table->dropColumn('ac_radicados_asignados');
        });
    }
}
