<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatedTableGsModelos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gs_modelos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('nombre',100);
            $table->string('descripcion',500)->nullable();
            $table->string('tabla',100);
            $table->string('ruta',500);
            $table->integer('padre');
            $table->boolean('estado');
            $table->integer('gs_modulo_id');
            $table->foreign('gs_modulo_id')->references('id')->on('gs_modulos')->onDelete('restrict');

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
        Schema::dropIfExists('gs_modelos');
    }
}
