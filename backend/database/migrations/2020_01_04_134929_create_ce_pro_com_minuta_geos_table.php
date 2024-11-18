<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCeProConMinutaGeosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ce_proconminutageos', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('ce_proconestprevio_id');
            $table->integer('porcentaje_contributivo');
            $table->integer('porcentaje_subsidiado');
            $table->integer('gn_municipio_id');

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
        Schema::dropIfExists('ce_proconminutageos');
    }
}
