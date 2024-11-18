<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMpIndicunirsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mp_indicunirs', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('ConOrden');
            $table->string('CodIndicacion',5);
            $table->integer('mp_medicamento_id')->unsigned();
            $table->foreign('mp_medicamento_id')->references('id')->on('mp_medicamentos')->onDelete('restrict');
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
        Schema::dropIfExists('mp_indicunirs');
    }
}
