<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRsCupIdFieldInAuReferenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('au_referencias', function (Blueprint $table) {
            $table->integer('rs_cup_id')->nullable();
            $table->foreign('rs_cup_id')->references('id')->on('rs_cupss')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('au_referencias', function (Blueprint $table) {
            $table->dropForeign('au_referencias_rs_cup_id_foreign');
            $table->dropColumn('rs_cup_id');
        });
    }
}
