<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableProconminutageosChangeValoresUPC extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ce_proconminutageos', function (Blueprint $table) {
            $table->decimal('valor_upc_subsidiado', 12, 2)->nullable()
                ->comment('VALOR UPC MENSUAL AÑO ACTUAL SUBSIDIADO')
                ->after('porcentaje_subsidiado')->change();
            $table->decimal('valor_upc_contributivo', 12, 2)->nullable()
                ->comment('VALOR UPC MENSUAL AÑO ACTUAL CONTRIBUTIVO')
                ->after('porcentaje_subsidiado')->change();
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
            $table->integer('valor_upc_subsidiado')->nullable()
                ->comment('VALOR UPC MENSUAL AÑO ACTUAL SUBSIDIADO')
                ->after('porcentaje_subsidiado')->change();
            $table->integer('valor_upc_contributivo')->nullable()
                ->comment('VALOR UPC MENSUAL AÑO ACTUAL CONTRIBUTIVO')
                ->after('porcentaje_subsidiado')->change();
        });
    }
}
