<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuAnet3MipresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::dropIfExists('au_anet3_mipres');
        Schema::create('au_anet3_mipres', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('au_anexot3_id')->unsigned();
            $table->timestamps();

//            $table->foreign('au_anexot3_id')->references('id')->on('au_anexot3s')->onUpdate('no action')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('au_anet3_mipres');
    }
}
