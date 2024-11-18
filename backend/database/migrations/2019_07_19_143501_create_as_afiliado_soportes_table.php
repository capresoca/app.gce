<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsAfiliadoSoportesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_afiliado_soportes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('gn_archivo_id');
            $table->integer('as_afiliado_id');
            $table->foreign('gn_archivo_id')->references('id')->on('gn_archivos')->onDelete('restrict');
            $table->foreign('as_afiliado_id')->references('id')->on('as_afiliados')->onDelete('restrict');
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
        Schema::dropIfExists('as_afiliado_soportes');
    }
}
