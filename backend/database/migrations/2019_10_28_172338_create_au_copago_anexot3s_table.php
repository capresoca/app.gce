<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuCopagoAnexot3sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('au_copago_anexot3s', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('as_afiliado_id')->index()->comment('Fk -Id del Afiliado');
            $table->double('copago')->default(0)->comment('Valor copago');
            $table->unsignedInteger('au_anext31_id')->comment('FK -Id del detalle anexo técnico');
            $table->dateTime('fecha_autorizacion')->comment('Fecha de la autorización');
            $table->timestamps();

            $table->foreign('as_afiliado_id')->references('id')->on('as_afiliados')->onDelete('restrict');
            $table->foreign('au_anext31_id')->references('id')->on('au_anexot31s')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('au_copago_anexot3s');
    }
}
