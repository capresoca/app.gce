<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMinutaFieldsInCeProconminutaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ce_proconminutas', function (Blueprint $table) {
            $table->longText('minuta')->nullable();
            $table->longText('minuta_original')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ce_proconminutas', function (Blueprint $table) {
            $table->dropColumn('minuta');
            $table->dropColumn('minuta_original');
        });
    }
}
