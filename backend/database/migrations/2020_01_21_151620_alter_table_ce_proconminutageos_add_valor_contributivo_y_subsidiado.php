<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableCeProconminutageosAddValorContributivoYSubsidiado extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ce_proconminutageos', function (Blueprint $table) {
            $table->integer('valor_upc_subsidiado')->nullable()
                ->comment('VALOR UPC MENSUAL AÑO ACTUAL SUBSIDIADO')
                ->after('porcentaje_subsidiado');
            $table->integer('valor_upc_contributivo')->nullable()
                ->comment('VALOR UPC MENSUAL AÑO ACTUAL CONTRIBUTIVO')
                ->after('porcentaje_subsidiado');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ce_proconminutageos', function (Blueprint $table) {
            $table->dropColumn('valor_upc_subsidiado');
            $table->dropColumn('valor_upc_contributivo');
        });
    }
}
