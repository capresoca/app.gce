<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmConcie10sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cm_concie10s', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cm_convisita_id');
            $table->integer('dx_relacionado');
            $table->longText('observaciones')->nullable();

            $table->foreign('cm_convisita_id')->references('id')->on('cm_convisitas')->onUpdate('no action')->onDelete('restrict');
            $table->foreign('dx_relacionado')->references('id')->on('rs_cie10s')->onUpdate('no action')->onDelete('restrict');

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
        Schema::dropIfExists('cm_concie10s');
    }
}
