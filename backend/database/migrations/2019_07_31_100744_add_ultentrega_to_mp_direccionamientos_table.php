<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUltentregaToMpDireccionamientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mp_direccionamientos', function (Blueprint $table) {
            $table->tinyInteger('UltEntrega')->default(0);
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
            $table->dropColumn('UltEntrega');
        });
    }
}
