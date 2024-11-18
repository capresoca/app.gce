<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOjAfiliadosTutelasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oj_afiliados_tutelas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('oj_tutela_id');
            $table->foreign('oj_tutela_id')->references('id')->on('oj_tutelas')->onDelete('restrict');
            $table->integer('as_afiliado_id')->nullable();
            $table->foreign('as_afiliado_id')->references('id')->on('as_afiliados')->onDelete('restrict');
            $table->integer('gn_tipdocidentidad_id')->nullable();
            $table->foreign('gn_tipdocidentidad_id')->references('id')->on('gn_tipdocidentidades')->onDelete('restrict');
            $table->string('identificacion','30')->nullable();
            $table->string('nombre_completo')->nullable();
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
        Schema::dropIfExists('oj_afiliados_tutelas');
    }
}
