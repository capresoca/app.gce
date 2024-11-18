<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsMsauditoriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_msauditorias', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('as_msubsidiado_id');
            $table->enum('accion', ['Crear Afiliado', 'Actualizar Afiliado','Generar Novedad']);
            $table->integer('id_registro');
            $table->text('detalle');
            $table->boolean('ejecutada');
            $table->foreign('as_msubsidiado_id')->references('id')->on('as_msubsidiados')->onDelete('RESTRICT');
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
        Schema::dropIfExists('as_msauditorias');
    }
}
