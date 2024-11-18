<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItemscontrisubsiportaCeProconminutas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ce_proconminutas', function (Blueprint $table) {
            $table->boolean('subsidiado')->default(false);
            $table->boolean('contributivo')->default(false);
            $table->boolean('portabilidad')->default(false);
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
            $table->dropColumn([
                'subsidiado',
                'contributivo',
                'portabilidad'
            ]);
        });
    }
}
