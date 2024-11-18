<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEntidadResolucionFieldToTsConceptoEgresosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::table('ts_concepto_egresos', function (Blueprint $table) {
//            $table->integer('pr_entidad_resolucion_ing_id')->nullable();
//            $table->integer('pr_entidad_resolucion_egr_id')->nullable();
//            $table->foreign('pr_entidad_resolucion_egr_id')->references('id')->on('pr_entidad_resoluciones');
//            $table->foreign('pr_entidad_resolucion_ing_id')->references('id')->on('pr_entidad_resoluciones');
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ts_concepto_egresos', function (Blueprint $table) {
            $table->dropForeign('ts_concepto_egresos_pr_entidad_resolucion_egr_id_foreign');
            $table->dropForeign('ts_concepto_egresos_pr_entidad_resolucion_ing_id_foreign');
            $table->dropColumn('pr_entidad_resolucion_ing_id');
            $table->dropColumn('pr_entidad_resolucion_egr_id');
        });
    }
}
