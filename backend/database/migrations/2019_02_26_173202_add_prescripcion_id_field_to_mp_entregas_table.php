<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrescripcionIdFieldToMpEntregasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mp_entregas', function (Blueprint $table) {
            $table->integer('mp_prescripcion_id')->unsigned();
            $table->integer('mp_tutela_id')->unsigned();

            $table->foreign('mp_prescripcion_id')->references('id')->on('mp_prescripciones')->onDelete('restrict');
            $table->foreign('mp_tutela_id')->references('id')->on('mp_tutelas')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mp_entregas', function (Blueprint $table) {
            $table->dropForeign('mp_entregas_mp_prescripcion_id_foreign');
            $table->dropForeign('mp_entregas_mp_tutela_id_foreign');
            $table->dropColumn('mp_prescripcion_id');
            $table->dropColumn('mp_tutela_id');
        });
    }
}
