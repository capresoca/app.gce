<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EntidadNullableTableCmConingresos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cm_coningresos', function (Blueprint $table) {
            $table->dropForeign('cm_coningresos_rs_entidad_id_foreign');
            $table->dropColumn('rs_entidad_id');

        });

        Schema::table('cm_coningresos', function (Blueprint $table){
            $table->integer('rs_entidad_id')->nullable()->after('via_ingreso');
            $table->foreign('rs_entidad_id')->references('id')->on('rs_entidades')->onUpdate('no action')->onDelete('restrict');
            $table->unique('cm_concurrencia_id');
        });

        Schema::table('cm_conegresos', function (Blueprint $table) {
            $table->dropForeign('cm_conegresos_cm_concurrencias_id_foreign');
            $table->dropColumn('cm_concurrencias_id');
        });

        Schema::table('cm_conegresos', function (Blueprint $table) {
            $table->integer('cm_concurrencia_id')->after('id');
            $table->foreign('cm_concurrencia_id')->references('id')->on('cm_concurrencias')->onUpdate('no action')->onDelete('restrict');
            $table->unique('cm_concurrencia_id');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cm_coningresos', function (Blueprint $table) {
            //
        });
    }
}
