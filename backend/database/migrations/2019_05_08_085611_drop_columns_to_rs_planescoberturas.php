<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnsToRsPlanescoberturas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rs_planescoberturas', function (Blueprint $table) {
            $table->dropForeign('fk_rs_contratos_rs_entidades1');
            $table->dropColumn(
                [
                    'fecha_contrato',
                    'fecha_acta_inicio',
                    'fecha_finalizacion',
                    'numero_contrato',
                    'rs_entidad_id',
                    'objeto',
                    'tipo',
                    'plazo_meses',
                    'plazo_dias',
                    'valor',
                    'valor_total',
                    'modificaciones_plazo',
                    'modificaciones_valor',
                    'subsidiado',
                    'contributivo'
                ]
            );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rs_planescoberturas', function (Blueprint $table) {
            //
        });
    }
}
