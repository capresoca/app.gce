<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEstadoToGnTercerosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gn_terceros', function (Blueprint $table) {
            $table->tinyInteger('estado')->nullable()->default(0);
            $table->tinyInteger('verificado')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gn_terceros', function (Blueprint $table) {
            $table->dropColumn('estado');
            $table->dropColumn('verificado');
        });
    }
}
