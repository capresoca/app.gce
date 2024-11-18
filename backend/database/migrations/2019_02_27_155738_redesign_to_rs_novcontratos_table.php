<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RedesignToRsNovcontratosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rs_novcontratos', function (Blueprint $table) {
            $table->dropForeign('fk_rs_novcontratos_rs_contratos1');
            $table->dropColumn('rs_contrato_id');
            $table->integer('ce_proconminuta_id');
            $table->foreign('ce_proconminuta_id')->references('id')->on('ce_proconminutas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rs_novcontratos', function (Blueprint $table) {
            $table->integer('rs_contrato_id');
            $table->foreign('rs_contrato_id','fk_rs_novcontratos_rs_contratos1')
                    ->references('id')->on('rs_contratos');
            $table->dropForeign('rs_novcontratos_ce_proconminuta_id_foreign');
            $table->dropColumn('ce_proconminuta_id');
        });
    }
}
