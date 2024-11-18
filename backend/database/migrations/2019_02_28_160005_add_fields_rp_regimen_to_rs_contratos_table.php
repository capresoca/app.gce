<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsRpRegimenToRsContratosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('rs_contratos', function (Blueprint $table) {

            $table->integer('regimen_atendido')->nullable();
            $table->integer('pr_rubro_id')->unsigned()->nullable();

            $table->foreign('regimen_atendido')->references('id')->on('as_regimenes')->onDelete('restrict');
            $table->foreign('pr_rubro_id')->references('id')->on('pr_detrps')->onDelete('restrict');

            $table->dropColumn('fecha_contrato');
            $table->dropColumn('fecha_acta_inicio');
            $table->dropColumn('fecha_finalizacion');
            $table->dropColumn('numero_contrato');
            $table->dropForeign('rs_contratos_rs_entidad_id_foreign');
            $table->dropColumn('rs_entidad_id');
            $table->dropColumn('objeto');
            $table->dropColumn('tipo');
            $table->dropColumn('plazo_meses');
            $table->dropColumn('plazo_dias');
            $table->dropColumn('valor');
            $table->dropColumn('valor_total');
            $table->dropColumn('modificaciones_plazo');
            $table->dropColumn('modificaciones_valor');
            $table->dropColumn('subsidiado');
            $table->dropColumn('contributivo');
            $table->dropColumn('estado');
            $table->string('descripcion');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rs_contratos', function (Blueprint $table) {
            $table->dropForeign('rs_contratos_pr_rubro_id_foreign');
            $table->dropForeign('rs_contratos_regimen_atendido_foreign');
            $table->dropColumn('regimen_atendido');
            $table->dropColumn('pr_rubro_id');
            $table->dateTime('fecha_contrato')->nullable();
            $table->dateTime('fecha_acta_inicio')->nullable();
            $table->dateTime('fecha_finalizacion')->nullable();
            $table->string('numero_contrato')->nullable();
            $table->integer('rs_entidad_id')->nullable();
            $table->foreign('rs_entidad_id')->references('id')->on('rs_entidades');
            $table->longText('objeto');
            $table->enum('tipo',['Capitado','Evento']);
            $table->integer('plazo_meses');
            $table->integer('plazo_dias');
            $table->double('valor');
            $table->double('valor_total');
            $table->integer('modificaciones_plazo');
            $table->integer('modificaciones_valor');
            $table->tinyInteger('subsidiado');
            $table->tinyInteger('contributivo');
            $table->enum('estado',['Registrado','Legalizado','Suspendido','Terminado']);
            $table->dropColumn('descripcion');
        });
    }
}
