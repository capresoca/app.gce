<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNfComdiarioLotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nf_comdiario_lotes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('nf_comdiarios_id');
            $table->timestamps();

            $table->foreign('nf_comdiarios_id')->references('id')->on('nf_comdiarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nf_comdiario_lotes');
    }
}
