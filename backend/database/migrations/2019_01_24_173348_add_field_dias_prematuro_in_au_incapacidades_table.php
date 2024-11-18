<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldDiasPrematuroInAuIncapacidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('au_incapacidades', function (Blueprint $table) {
            $table->integer('dias_prematuro')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('au_incapacidades', function (Blueprint $table) {
            $table->dropColumn('dias_prematuro');
        });
    }
}
