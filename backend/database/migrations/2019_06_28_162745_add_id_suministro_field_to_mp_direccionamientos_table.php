<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdSuministroFieldToMpDireccionamientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mp_direccionamientos', function (Blueprint $table) {
            $table->bigInteger('suministro_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mp_direccionamientos', function (Blueprint $table) {
            $table->dropColumn('suministro_id');
        });
    }
}
