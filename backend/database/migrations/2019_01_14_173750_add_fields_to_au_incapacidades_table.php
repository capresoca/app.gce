<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToAuIncapacidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('au_incapacidades', function (Blueprint $table) {
            $table->float('total_a_pagar')->nullable();
            $table->float('ibc_final')->nullable();
            $table->float('valor_a_reconocer_diario')->nullable();
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
            $table->dropColumn('total_a_pagar');
            $table->dropColumn('ibc_final');
            $table->dropColumn('valor_a_reconocer_diario');
        });
    }
}
