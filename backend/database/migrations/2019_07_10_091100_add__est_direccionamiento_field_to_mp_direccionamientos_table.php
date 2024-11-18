<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEstDireccionamientoFieldToMpDireccionamientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mp_direccionamientos', function (Blueprint $table) {
            $table->tinyInteger('EstDireccionamiento')->nullable();
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
            $table->dropColumn('EstDireccionamiento');
        });
    }
}
