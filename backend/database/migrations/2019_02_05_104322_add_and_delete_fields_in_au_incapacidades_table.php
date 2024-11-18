<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAndDeleteFieldsInAuIncapacidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('au_incapacidades', function (Blueprint $table) {
            $table->dropColumn('base_liquidacion');
            $table->dropColumn('ibc_final');
            $table->double('ibc_calculado')->nullable();
            $table->double('ibc_pagado')->nullable();
            $table->integer('dias_calculado')->nullable();
            $table->integer('dias_pagado')->nullable();
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
            $table->double('base_liquidacion')->nullable();
            $table->double('ibc_final')->nullable();
            $table->dropColumn('ibc_calculado');
            $table->dropColumn('ibc_pagado');
            $table->dropColumn('dias_calculado');
            $table->dropColumn('dias_pagado');
        });
    }
}
