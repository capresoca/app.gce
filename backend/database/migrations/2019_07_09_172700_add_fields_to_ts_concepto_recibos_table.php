<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToTsConceptoRecibosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ts_concepto_recibos', function (Blueprint $table) {
            $table->dropColumn('cod_retencion');
            $table->dropColumn('facturacion');
            $table->dropColumn('anticipos');
            $table->dropColumn('pacientes');
        });
        Schema::table('ts_concepto_recibos', function (Blueprint $table) {
            $table->boolean('facturacion')->default(0);
            $table->boolean('anticipos')->default(0);
            $table->boolean('pacientes')->default(0);
            $table->integer('nf_conrtf_id')->nullable();
            $table->integer('pr_entidad_resolucion_id')->nullable();
            $table->integer('pr_detstringreso_id')->unsigned()->nullable();

            $table->foreign('nf_conrtf_id')->references('id')->on('nf_conrtfs')->onDelete('restrict');
            $table->foreign('pr_entidad_resolucion_id')->references('id')->on('pr_entidad_resoluciones');
            $table->foreign('pr_detstringreso_id')->references('id')->on('pr_detstringresos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ts_concepto_recibos', function (Blueprint $table) {
            $table->string('cod_retencion',255);
            $table->dropColumn('facturacion');
            $table->dropColumn('anticipos');
            $table->dropColumn('pacientes');

            $table->dropForeign('ts_concepto_recibos_nf_conrtf_id_foreign');
            $table->dropForeign('ts_concepto_recibos_pr_entidad_resolucion_id_foreign');
            $table->dropForeign('ts_concepto_recibos_pr_detstringreso_id_foreign');

        });
        Schema::table('ts_concepto_recibos', function (Blueprint $table) {

            $table->enum('facturacion',['Si','No']);
            $table->enum('anticipos',['Si','No']);
            $table->enum('pacientes',['Si','No']);

            $table->dropColumn('nf_conrtf_id');
            $table->dropColumn('pr_entidad_resolucion_id');
            $table->dropColumn('pr_detstringreso_id');

        });
    }
}
