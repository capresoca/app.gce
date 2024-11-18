<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItemsCmConvisitas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cm_convisitas', function (Blueprint $table) {
            // Agregar campos a tabla cm_convisitas
            $table->float('fio2',4,2);
            $table->float('peep',4,2);
            $table->float('frec_ve',4,2);
            $table->string('modalidad');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cm_convisitas', function (Blueprint $table) {
            //
        });
    }
}
