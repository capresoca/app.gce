<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RedesingToCeProconminutasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        DB::statement("ALTER TABLE `ce_proconminutas`
	                    CHANGE COLUMN `ce_procontractuale_id` `ce_procontractuale_id` INT(11) NULL COMMENT 'FK - Proceso contractual' AFTER `id`;");

        Schema::table('ce_proconminutas', function (Blueprint $table) {
            $table->datetime('fecha_contrato');
            $table->datetime('fecha_acta_inicio')->nullable();
            $table->datetime('fecha_finalizacion')->nullable();
            $table->string('numero_contrato',50)->nullable();
            $table->integer('rs_entidad_id');
            $table->text('objeto')->nullable();
            $table->enum('tipo',['Capitado','Evento'])->nullable();
            $table->integer('plazo_meses')->nullable();
            $table->integer('plazo_dias')->nullable();
            $table->double('valor')->nullable();
            $table->double('valor_total')->nullable();
            $table->integer('modificaciones_plazo')->nullable();
            $table->integer('modificaciones_valor')->nullable();
            $table->integer('gs_usuario_id');

            $table->foreign('rs_entidad_id')->references('id')->on('rs_entidades');
            $table->foreign('gs_usuario_id')->references('id')->on('gs_usuarios');
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
            $table->dropForeign('mp_entregas_rs_entidad_id_foreign');
            $table->dropForeign('mp_entregas_gs_usuario_id_foreign');
            $table->dropColumn('fecha_contrato');
            $table->dropColumn('fecha_acta_inicio');
            $table->dropColumn('fecha_finalizacion');
            $table->dropColumn('numero_contrato');
            $table->dropColumn('rs_entidad_id');
            $table->dropColumn('objeto');
            $table->dropColumn('tipo',['Capitado','Evento']);
            $table->dropColumn('plazo_meses');
            $table->dropColumn('plazo_dias');
            $table->dropColumn('valor');
            $table->dropColumn('valor_total');
            $table->dropColumn('modificaciones_plazo');
            $table->dropColumn('modificaciones_valor');
            $table->dropColumn('gs_usuario_id');

        });
    }
}



