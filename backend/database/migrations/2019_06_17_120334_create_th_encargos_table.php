<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThEncargosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('th_encargos', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha_inicio')->comment('Fecha de inicio de funciones del encargado.');
            $table->date('fecha_fin')->comment('Fecha final de funciones del encargado.');
            $table->longText('concepto_encargo')->nullable()->comment('Descripción del porque del encargo.');
            $table->integer('gs_usuario_id')->comment('FK - ID del Usuario Encargado.');
            $table->foreign('gs_usuario_id')->references('id')->on('gs_usuarios')->onDelete('restrict');
            $table->integer('pr_entidad_resolucion_id')->nullable()->comment('FK - ID de la Entidad Resolución actual.');
            $table->foreign('pr_entidad_resolucion_id')->references('id')->on('pr_entidad_resoluciones')->onDelete('restrict');
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
        Schema::dropIfExists('th_encargos');
    }
}
