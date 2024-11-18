<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCeProconminutaIdToRsContratosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rs_contratos', function (Blueprint $table) {
            $table->integer('ce_proconminuta_id')->nullable();
            $table->foreign('ce_proconminuta_id')->references('id')
                    ->on('ce_proconminutas')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rs_contratos', function (Blueprint $table) {
            $table->dropForeign('rs_contratos_ce_proconminuta_id_foreign');
            $table->dropColumn('ce_proconminuta_id');
        });
    }
}
