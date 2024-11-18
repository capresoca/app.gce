<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMpNovetutelasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mp_novetutelas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('TipoNov')->nullable();
            $table->string('NoPrescripcion');
            $table->string('NoPrescripcionF')->nullable();
            $table->string('FNov')->nullable();
            $table->integer('mp_tutela_id')->unsigned();
            $table->integer('mp_tutelafinal_id')->unsigned()->nullable();
            $table->foreign('mp_tutela_id')->references('id')->on('mp_tutelas');
            $table->foreign('mp_tutelafinal_id')->references('id')->on('mp_tutelas');
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
        Schema::dropIfExists('mp_novetutelas');
    }
}
