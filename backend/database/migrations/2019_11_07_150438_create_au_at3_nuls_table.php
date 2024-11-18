<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuAt3NulsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('au_at3_nuls', function (Blueprint $table) {
            $table->increments('id');
            $table->string('observacion');
            $table->unsignedInteger('au_anexot3_id');
            $table->timestamps();

            $table->foreign('au_anexot3_id')->references('id')->on('au_anexot3s')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('au_at3_nuls');
    }
}
