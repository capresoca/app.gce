<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmConcumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cm_concums', function (Blueprint $table) {
            $table->increments('id');
            $table->double('cantidad');
            $table->longText('observaciones')->nullable();
            $table->double('valor');
            $table->integer('cm_convisita_id');
            $table->integer('rs_cum_id');

            $table->foreign('cm_convisita_id')->references('id')->on('cm_convisitas')->onUpdate('no action')->onDelete('restrict');
            $table->foreign('rs_cum_id')->references('id')->on('rs_cums')->onUpdate('no action')->onDelete('restrict');

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
        Schema::dropIfExists('cm_concums');
    }
}
