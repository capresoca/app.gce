<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDetstringresoFieldToTsConceptosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ts_concepto_egresos', function (Blueprint $table) {
            $table->integer('pr_detstringreso_id')->unsigned();
            $table->foreign('pr_detstringreso_id')->references('id')->on('pr_detstringresos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ts_concepto_egresos', function (Blueprint $table) {
            $table->dropForeign('ts_concepto_egresos_pr_detstringreso_id_foreign');
            $table->dropColumn('pr_detstringreso_id');
        });
    }
}
