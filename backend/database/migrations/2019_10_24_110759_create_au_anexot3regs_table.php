<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuAnexot3regsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('au_anexot3regs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('as_regimene_id');
            $table->unsignedInteger('au_anexot3_id');
            $table->timestamps();

            $table->foreign('as_regimene_id')->references('id')->on('as_regimenes')->onUpdate('no action')->onDelete('restrict');
            $table->foreign('au_anexot3_id')->references('id')->on('au_anexot3s')->onUpdate('no action')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('au_anexot3regs');
    }
}
