<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFieldRsContratoIdForeignInRsServcontratadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rs_servcontratados', function (Blueprint $table) {
            $table->float('porcentaje_afiliados',12,2)->after('rs_contrato_id')->change();
            $table->double('porcentaje_contratado',12,2)->after('rs_servicio_id')->default(0)->comment('Porcetaje del servivico contratado');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rs_servcontratados', function (Blueprint $table) {
            $table->float('porcentaje_afiliados')->after('rs_contrato_id')->change();
            $table->dropColumn('porcentaje_contratado');
        });
    }
}
