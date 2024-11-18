<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFieldEnviarGloasInCmRadicadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cm_radicados', function (Blueprint $table) {
            $table->boolean('enviar_glosas')->after('rs_rip_radicado_id')->default('0')->comment('Campo para identificar si las cuentas.');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cm_radicados', function (Blueprint $table) {
            $table->dropColumn('enviar_glosas');
        });
    }
}
