<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RefactorInCeProconestpreviosTable extends Migration
{
    public function up()
    {
        Schema::table('ce_proconestprevios', function (Blueprint $table) {
            $table->dropForeign('fk_ce_proconestprevios_gn_municpios1');
            $table->dropColumn('gn_lugejecucion_id');
            $table->dropForeign('fk_ce_proconestprevios_pr_rubros1');
            $table->dropColumn('pr_rubro_id');
            $table->text('lugar_ejecucion');

            $table->longText('productos_entregar');
            $table->dropColumn('obj_contratar');
            $table->dropColumn('tipo');
            $table->dropColumn('plazo');
            $table->integer('plazo_meses');
            $table->integer('plazo_dias');
            $table->longText('tarifas')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ce_proconestprevios', function (Blueprint $table) {
            $table->dropColumn('lugar_ejecucion');
            $table->integer('gn_lugejecucion_id');
            $table->foreign('gn_lugejecucion_id','fk_ce_proconestprevios_gn_municpios1')
                ->references('id')->on('gn_municipios');

            $table->integer('pr_rubro_id');
            $table->foreign('pr_rubro_id','fk_ce_proconestprevios_pr_rubros1')
                    ->references('id')->on('pr_rubros');

            $table->dropColumn('productos_entregar');
            $table->longText('obj_contratar');
            $table->enum('tipo',['Servicios de Salud','Otro']);
            $table->integer('plazo');
            $table->dropColumn('plazo_meses');
            $table->dropColumn('plazo_dias');
            $table->dropColumn('tarifas');
        });
    }
}
