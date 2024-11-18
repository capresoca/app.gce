<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMinutaFirmadaFieldToCeProconminutasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ce_proconminutas', function (Blueprint $table) {
            $table->integer('minuta_firmada')->nullable();
            $table->foreign('minuta_firmada')->references('id')->on('gn_archivos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ce_proconminutas', function (Blueprint $table) {
            $table->dropForeign('ce_proconminutas_minuta_firmada_foreign');
            $table->dropColumn('minuta_firmada');
        });
    }
}
