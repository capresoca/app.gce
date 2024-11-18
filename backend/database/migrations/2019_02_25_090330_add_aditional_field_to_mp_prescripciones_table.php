<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAditionalFieldToMpPrescripcionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mp_prescripciones', function (Blueprint $table) {
            $table->enum('regimen',['subsidiado','contributivo'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mp_prescripciones', function (Blueprint $table) {
            $table->dropColumn('regimen');
        });
    }
}
