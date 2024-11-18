<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCie10ComplicacionFieldInCmFacinternacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cm_facinternaciones', function (Blueprint $table) {
            $table->string('complicacion',5)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cm_facinternaciones', function (Blueprint $table) {
            $table->dropColumn('complicacion');
        });
    }
}
