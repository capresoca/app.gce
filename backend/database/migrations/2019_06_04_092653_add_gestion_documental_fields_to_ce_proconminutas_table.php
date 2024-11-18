<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGestionDocumentalFieldsToCeProconminutasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ce_tipocontratos', function (Blueprint $table) {
            $table->string('codigo_documento',20)->nullable();
            $table->date('fecha_version')->nullable();
            $table->string('version')->nullable();
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
            $table->dropColumn('codigo_documento');
            $table->dropColumn('fecha_version');
            $table->dropColumn('version');
        });
    }
}
