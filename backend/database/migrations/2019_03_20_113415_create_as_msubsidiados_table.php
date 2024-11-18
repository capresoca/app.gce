<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsMsubsidiadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_msubsidiados', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fecha_archivo');
            $table->string('nombre_archivo');
            $table->integer('numero_filas');
            $table->integer('gs_usuario_id');
            $table->foreign('gs_usuario_id')->references('id')->on('gs_usuarios')->onDelete('RESTRICT');
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
        Schema::dropIfExists('as_msubsidiados');
    }
}
