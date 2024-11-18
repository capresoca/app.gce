<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmConcupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cm_concups', function (Blueprint $table) {
            $table->increments('id');
            $table->double('cantidad');
            $table->double('valor');
            $table->longText('observaciones')->nullable();
            $table->integer('rs_cup_id');
            $table->integer('cm_convisita_id');

            $table->foreign('rs_cup_id')->references('id')->on('rs_cupss')->onUpdate('no action')->onDelete('restrict');
            $table->foreign('cm_convisita_id')->references('id')->on('cm_convisitas')->onUpdate('no action')->onDelete('restrict');

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
        Schema::dropIfExists('cm_concups');
    }
}
