<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmRespuestasGlosasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cm_respuestas_glosas', function (Blueprint $table) {
            $table->increments('id')->comment('Id de la tabla de respuestas de las glosas.');
            $table->unsignedBigInteger('cm_glosa_id')->nullable()->comment('FK - De la glosa respondida.');
            $table->foreign('cm_glosa_id')->references('id')->on('cm_glosas')->onDelete('restrict');
            $table->integer('cm_respuesta_manglosa_id')->nullable()->default(null)->comment('FK - De la glosa seleccionada como respuesta.');
            $table->foreign('cm_respuesta_manglosa_id')->references('id')->on('cm_manglosas')->onDelete('restrict');
            $table->integer('gs_usuario_id')->nullable()->comment('FK - Del usuario que registra la respuesta de la glosa.');
            $table->foreign('gs_usuario_id')->references('id')->on('gs_usuarios')->onDelete('restrict');
            $table->enum('estado',['Subsanada','Aceptada','Ratificada'])->nullable()->comment('Estado de la respuesta auditada.');
            $table->longText('observacion')->nullable()->comment('Campo para anotar el concepto de la respuesta d ela glosa.');
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
        Schema::dropIfExists('cm_respuestas_glosas');
    }
}
