<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPlantillaminutaInCeTipocontratosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ce_tipocontratos', function (Blueprint $table) {
            $table->longText('plantillaminuta')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ce_tipocontratos', function (Blueprint $table) {
            $table->dropColumn('plantillaminuta');
        });
    }
}
