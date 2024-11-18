<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMpFalloAdicionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mp_fallo_adicionals', function (Blueprint $table) {
            $table->increments('id');
            $table->date('FFalloAdic');
            $table->string('NroFalloAdic',50);
            $table->integer('mp_tutela_id')->unsigned();
            $table->foreign('mp_tutela_id')->references('id')->on('mp_tutelas')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mp_fallo_adicionals');
    }
}
