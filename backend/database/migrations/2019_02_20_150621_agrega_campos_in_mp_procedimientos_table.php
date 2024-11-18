<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregaCamposInMpProcedimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mp_procedimientos', function (Blueprint $table) {
            $table->dropForeign('mp_procedimientos_rs_cups_id_foreign');
            $table->dropColumn('rs_cups_id');
            $table->string('JustNoPBS', 500)->nullable();
            $table->string('IndRec',160)->nullable();
            $table->tinyInteger('EstJM')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mp_procedimientos', function (Blueprint $table) {
            $table->dropColumn('JustNoPBS');
            $table->dropColumn('IndRec');
            $table->dropColumn('EstJM');
        });
    }
}
