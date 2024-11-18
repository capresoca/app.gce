<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmConcondclinicaCmConvisitaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cm_concondclinica_cm_convisita', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cm_convisita_id');
            $table->integer('cm_concondclinica_id')->unsigned();

            $table->foreign('cm_convisita_id')->references('id')->on('cm_convisitas')->onUpdate('no action')->onDelete('restrict');
            $table->foreign('cm_concondclinica_id')->references('id')->on('cm_concondclinicas')->onUpdate('no action')->onDelete('restrict');

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
        Schema::dropIfExists('cm_concondclinica_cm_convisita');
    }
}
