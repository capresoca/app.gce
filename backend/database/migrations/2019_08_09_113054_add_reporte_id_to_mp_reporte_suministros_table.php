<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReporteIdToMpReporteSuministrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mp_reporte_suministros', function (Blueprint $table) {
            $table->bigInteger('reporte_id')->unsigned()->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mp_reporte_suministros', function (Blueprint $table) {
            $table->dropColumn('reporte_id');
        });
    }
}
