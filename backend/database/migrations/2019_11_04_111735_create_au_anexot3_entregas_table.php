<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuAnexot3EntregasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('au_anexot3_entregas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('au_anexot3s_id');
            $table->string('recibido');
            $table->timestamps();

            $table->foreign('au_anexot3s_id')->references('id')->on('au_anexot3s')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('au_anexot3_entregas');
    }
}
