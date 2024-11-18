<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TutelaIdFieldToMpDireccionamientos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mp_direccionamientos', function (Blueprint $table) {
            $table->integer('mp_prescripcion_id')->unsigned()->nullable()->change();
            $table->integer('mp_tutela_id')->nullable()->unsigned();
            $table->foreign('mp_tutela_id')->references('id')->on('mp_tutelas');
            $table->foreign('mp_prescripcion_id')->references('id')->on('mp_prescripciones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mp_direccionamientos', function (Blueprint $table) {
            $table->dropForeign('mp_direccionamientos_mp_tutela_id_foreign');
            $table->dropForeign('mp_direccionamientos_mp_prescripcion_id_foreign');
        });
    }
}
