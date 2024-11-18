<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddForeignMasivoToRsMaestroipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rs_maestroips', function (Blueprint $table) {
            $table->unsignedInteger('rs_carguegrupoips_id')->index()->nullable()->after('gs_usuariactualizacion_id')->comment('Fk -Id del Cargue');
        });

        Schema::table('rs_maestroipshistoricos', function (Blueprint $table) {
            $table->unsignedInteger('rs_carguegrupoips_id')->index()->nullable()->after('gs_usuario_id')->comment('Fk -Id del Cargue');
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
            $table->dropColumn('rs_carguegrupoips_id');
        });

        Schema::table('rs_maestroipshistoricos', function (Blueprint $table) {
            $table->dropColumn('rs_carguegrupoips_id');
        });
    }
}
