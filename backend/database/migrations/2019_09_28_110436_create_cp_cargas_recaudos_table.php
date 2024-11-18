<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCpCargasRecaudosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_cargas_recaudos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('numero_archivos_zip');
            $table->string('nombre_archivos_zip');
            $table->integer('numero_archivos_txt');
            $table->enum('estado_carga',['Completa','Incompleta'])->nullable();
            $table->integer('gs_usuario_id');
            $table->foreign('gs_usuario_id')->references('id')->on('gs_usuarios')->onDelete('restrict')->onUpdate('no action');
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
        Schema::dropIfExists('as_cargas_recaudos');
    }
}
