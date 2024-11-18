<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddFieledsInRsMaestroepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rs_maestroips', function (Blueprint $table) {
            $table->integer('rs_portabilidade_id')->index()->nullable()->default(null)->after('as_afiliado_id');
            $table->integer('gs_usuario_id')->index()->nullable()->default(null)->after('rs_portabilidade_id');
            $table->integer('gs_usuariactualizacion_id')->index()->nullable()->default(null)->after('gs_usuario_id');
            $table->unique(['id_grupoips','as_afiliado_id'],'id_grupoips_as_afiliado_id');
            $table->unique(['id_grupoips','as_afiliado_id','rs_portabilidade_id'],'id_grupoips_as_afiliado_id_rs_portabilidade_id');
        });

        Schema::table('rs_maestroipsgrups', function (Blueprint $table) {
            $table->boolean('portable')->nullable()->default(0)->after('descrip');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rs_maestroips', function (Blueprint $table) {
            $table->dropColumn('rs_portabilidade_id');
            $table->dropColumn('gs_usuario_id');
            $table->dropColumn('gs_usuariactualizacion_id');
        });

        Schema::table('rs_maestroipsgrups', function (Blueprint $table) {
            $table->dropColumn('portable');
        });
    }
}
