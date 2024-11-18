<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmGlosasConciliadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cm_glosas_conciliadas', function (Blueprint $table) {
            $table->increments('id')->comment('Id de las glosas registradas para conciliación.');
            $table->unsignedInteger('cm_respuestas_glosa_id')->nullable()->comment('FK - De la glosa respondida.');
            $table->foreign('cm_respuestas_glosa_id')->references('id')->on('cm_respuestas_glosas')->onDelete('restrict');
            $table->integer('cm_manglosa_con_id')->nullable()->default(null)->comment('FK - De la glosa seleccionada para conciliación.');
            $table->foreign('cm_manglosa_con_id')->references('id')->on('cm_manglosas')->onDelete('restrict');
            $table->integer('gs_usuario_id')->nullable()->comment('FK - Del usuario que registra la conciliación de la glosa.');
            $table->foreign('gs_usuario_id')->references('id')->on('gs_usuarios')->onDelete('restrict');
            $table->longText('observacion')->nullable()->comment('Campo para anotar el concepto de la conciliación glosa.');
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
        Schema::dropIfExists('cm_glosas_conciliadas');
    }
}
