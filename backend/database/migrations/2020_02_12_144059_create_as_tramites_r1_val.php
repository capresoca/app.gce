<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsTramitesR1Val extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_tramites_r1_val', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('as_tramite_id');
            $table->date('fecha_afiliacion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('as_tramites_r1_val');
    }
}
